<?php 
include "helper.php";
function getTitle(){
	return "Admin Panel";
}
session_start();
mysql_connect(DB_HOST,DB_USER,DB_PASS);
mysql_select_db(DB_BASE);
$x=$_GET;
$p=array();
$d=dir('tpl');
while($e=$d->read())
	if($e!="."&&$e!="..")
		q("insert into t values(NULL,'".$e."')");
$d=array();
$d[0]=$d[1]=$d[2]=$d[3]=$d[4]="";
$q=q("select * from t");
while($r=f($q))
	$p[]=$r;
if(isset($_SESSION["a"])){
	if(isset($x["v"])){
		q("update p set c='".e($x["c"])."',p=".$x["p"].",n='".e(u($x))."',t='".e($x["t"])."' where i=".$x["v"]);
		r();
	}
	if(isset($x["t"])){
		q("insert into p values(NULL,'".e($x["c"])."',".$x["p"].",'".e(u($x))."','".e($x["t"])."')");
		r();
	}
	if(isset($x["d"])){
		q("delete from p where i=".$x["d"]);
		r();
	}
	if(isset($x["e"]))
		$d=f(q("select t,n,p,c,i from p where i=".$x["e"])); /* all todo*/
	
	showHeader();
	$q=q("select * from p");
	while($r=f($q))
		echo "<p><a href='".absPath()."/$r[3]/'>$r[4]</a> <a href='?e=$r[0]'>edit</a> <a href='?d=$r[0]'>del</a></p>"; 
?>
		<form href="">
<?php 
			echo ($d[0]!=""?"<input type='hidden' name='v' value='$d[4]'/>":"");
			i("Titre*","t",$d[0]);
			i("Url","u",$d[1]); 
?>
			<p>Template*
				<select name="p">
					<?php foreach($p as $t) ?>
						<option value="<?php echo $t[0].'" '.($t[0]==$d[2]?"selected":""); ?>><?php echo $t[1]; ?></option>
				</select>
			</p>
			<p class="tContent">Contenu*</p>
			<textarea name="c"><?php echo $d[3]; ?></textarea>
			<input type="submit" value="Valider"/>
			<div class="clear"></div>
			</form>
<?php 
}
else{
	if(isset($x["i"])){
		if(f(q("select * from u where u.n='".$x["i"]."' and u.p='".md5($x["p"])."'")))
			$_SESSION["a"]=1;
		r();
	}
	showHeader();
?>
	<form class="login" href="">
		<?php i("ID","i",""); ?>
		<p>Pwd<input type="password" name="p"/></p>
		<input type="submit" value="Valider"/>
		<div class="clear"></div>
	</form>
<?php 
}
showFooter();