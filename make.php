<?php


class Compiler {
	
	private $src, $bin, $baseFile, $htaccess;
	private $folders = "", $files = "";
	private $unpacker;

	
	public function __construct($src, $bin, $baseFile, $defHost="127.0.0.1", $defUser="root", $defPass="", $defBase="4k") {
		$this->src = $src;
		$this->bin = $bin;
		$this->baseFile = $baseFile;
		$this->unpacker =	'$s=256;$b=8;$r=$l=$j=$i=0;$t=range("\0","\xFF");$z="";for(;$i<LOAD_SIZE;$i++)' .
							'{$r=($r<<8)+ord($d[$i]);$l+=8;if($l>=$b){$l-=$b;$e=$t[$r>>$l];if(!isset($e)' .
							')$e=$w.$w[0];$z.=$e;if($j++)$t[]=$w.$e[0];$w=$e;$r&=(1<<$l)-1;if(++$s>>$b)$' .
							'b++;}}$h="DB_HOST";$u="DB_USER";$p="DB_PASS";$b="DB_BASE";eval($z);';
		$this->unpacker = str_replace(array('DB_HOST', 'DB_USER', 'DB_PASS', 'DB_BASE'), array($defHost, $defUser, $defPass, $defBase), $this->unpacker);
	}
	
	public function run() {
		$this->generateArboTree($this->src);
		$this->generateLoad();
	}
	
	private function minPHPEscape($file) {
		$t = str_replace('\\', '\\\\', $this->minPHP($file));
		$t = str_replace('\'', '\\\'', $t);
		
		return $t;
	}
	
	private function minPHP($file) {
		$t = file_get_contents($file);
		$t = preg_replace('/\n|\r/','',$t);
		
		return $t;
	}
	
	private function minOther($file) {
		return file_get_contents($file);
	}
	
	private function minifyFile($file) {
		$ext = substr($file, -3);
		switch($ext) {
			case "php": return $this->minPHPEscape($file);
			default: return $this->minOther($file);
		}
	}
	
	private function generateArboTree($rep) {
		$dir = opendir($rep); 
		while($file = readdir($dir)) {
			if($file != '.' && $file != '..' && $file != $this->baseFile) {
				$f = str_replace("$this->src/", '', "$rep/$file");
				if(is_file("$rep/$file")) {
					$fileContent = $this->minifyFile("$rep/$file");
					if(preg_match("/DB_HOST|DB_USER|DB_PASS|DB_BASE/", $fileContent)) {
						$fileContent = 'str_replace(array("DB_HOST","DB_USER","DB_PASS","DB_BASE"),array("\'$h\'","\'$u\'","\'$p\'","\'$b\'"),\'' . $fileContent . '\')';
						$this->files .= 'fputs($f=fopen("' . $f . '","w"),' . $fileContent . ');fclose($f);';
					}
					else {
						$this->files .= 'fputs($f=fopen("' . $f . '","w"),\'' . $fileContent . '\');fclose($f);';
					}
				}
				else {
					$this->folders .= 'mkdir("' . $f . '");';
					$this->generateArboTree("$rep/$file");
				}
			}
		}

		closedir($dir);
	}
	
	private function generateLoad() {
		$content = substr($this->minPHP("$this->src/$this->baseFile"), 6);
		$content = str_replace('/* ADD FILES HERE */', $this->folders.$this->files, $content);
				
		$content = $this->lzwCompress($content);
		$content = 'error_reporting(0);$d=\'' . str_replace('\'', '\\\'', str_replace("\\", "\\\\", $content)) . "';" . str_replace("LOAD_SIZE", "".strlen($content), $this->unpacker);
		
		$fp = fopen("$this->bin/$this->baseFile","w");
		fputs($fp, "<?php " . $content);
		fclose($fp);		
	}
	
	private function lzwCompress($string) {
		$dictionary = array_flip(range("\0", "\xFF"));
		$word = "";
		$codes = array();
		$dictionary_count = 256;
		$bits = 8;
		$return = "";
		$rest = 0;
		$rest_length = 0;
		
		for($i=0; $i <= strlen($string); $i++) {
			$x = $string[$i];
			if(strlen($x) && isset($dictionary[$word . $x])) {
				$word .= $x;
			}
			else if($i) {
				$codes[] = $dictionary[$word];
				$dictionary[$word . $x] = count($dictionary);
				$word = $x;
			}
		}
		foreach ($codes as $code) {
			$rest = ($rest << $bits) + $code;
			$rest_length += $bits;
			$dictionary_count++;
			if($dictionary_count >> $bits) {
				$bits++;
			}
			while($rest_length > 7) {
				$rest_length -= 8;
				$return .= chr($rest >> $rest_length);
				$rest &= (1 << $rest_length) - 1;
			}
		}
		return $return . ($rest_length ? chr($rest << (8 - $rest_length)) : "");
	}
};

$c = new Compiler("src", "bin", "index.php");
$c->run();

// Clean not proper, but works for now.
unlink("bin/header.php");
unlink("bin/footer.php");
unlink("bin/.htaccess");
unlink("bin/style.css");
unlink("bin/admin.php");
unlink("bin/tpl/404.php");
unlink("bin/tpl/post.php");
rmdir("bin/tpl");

mysql_connect("127.0.0.1","root","");
mysql_select_db("4k");
mysql_query("drop table u, p, t");
mysql_close();
