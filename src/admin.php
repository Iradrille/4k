<?php 
function getTitle(){
	return "Admin Panel";
}

function r(){
	header('location:admin.php');
}

function absPath(){
	$a=$_SERVER["PHP_SELF"];
	return substr($a,0,strlen($a)-10);
}

function i($l,$n){
	echo "<p>$l<input type='text' name='$n'/></p>";
}

session_start();
mysql_connect(DB_HOST,DB_USER,DB_PASS);
mysql_select_db(DB_BASE);
$w=$_POST;
$p=array();
$d=dir('tpl');
while($e=$d->read()){
	if($e!="."&&$e!="..")
		mysql_query("insert into t values(NULL,'".$e."')");
}
$q=mysql_query("select * from t");
while($r=mysql_fetch_array($q))
	$p[]=$r;
if(isset($_SESSION["a"])){
	if(isset($w["t"])){
		$u=(isset($w["u"])&&strlen($w["u"]))?$w["u"]:str_replace(' ','-',$w["t"]);
		mysql_query("insert into p values(NULL,'".$w["c"]."',".$w["p"].",'".$u."','".$w["t"]."')");
		r();
	}
	else{
		include 'header.php'; 
?>
		<form href="" method="post">
<?php 
			i("Titre*","t");
			i("Url","u"); 
?>
			<p>Template*
				<select name="p">
					<?php foreach($p as $t){ ?>
						<option value="<?php echo $t[0]; ?>"><?php echo $t[1]; ?></option>
					<?php } ?>
				</select>
			</p>
			<p class="tContent">Contenu*</p>
			<textarea name="c"></textarea>
			<input type="submit" value="Valider"/>
			<div class="clear"></div>
			</form>
<?php 	}
	}
	else{
	if(isset($w["i"])){
		if(mysql_fetch_array(mysql_query("select * from u where u.n='".$w["i"]."' and u.p='".md5($w["p"])."'")))
			$_SESSION["a"]=1;
		r();
	}
	else{
		include 'header.php'; 
?>
			<form class="login" href="" method="post">
				<?php i("ID","i"); ?>
				<p>Pwd<input type="password" name="p"/></p>
				<input type="submit" value="Valider"/>
				<div class="clear"></div>
			</form>
<?php 	}
	}
	include 'footer.php';