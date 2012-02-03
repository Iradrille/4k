<?php 
function l($f,$c){
	foreach($f as $g){
		if(file_exists($g="tpl/".$g)){
			$_SESSION["c"]=$c["c"];
			$_SESSION["t"]=$c["t"];
			include $g;
			exit;
		}
	}
}

function getTitle(){
	return $_SESSION["t"];
}

function getContent(){
	return $_SESSION["c"];
}

function showHeader(){
	include "header.php";
}

function showFooter(){
	include "footer.php";
}

function absPath(){
	$a=$_SERVER["PHP_SELF"];
	return substr($a,0,strlen($a)-10);
}

function q($q){
	return mysql_query($q);
}

mysql_connect($h,$u,$p);
mysql_select_db($b);
if(file_exists("header.php"))goto s;
/* ADD FILES HERE */
q("create table p(i int(9) not null auto_increment,c text,p int(9),n varchar(99),t varchar(99),primary key(i))engine=InnoDB default charset=utf8 auto_increment=2");
q("insert into p values (1,'Installation de 4k réussie. Les logs du <a href=\'".absPath()."/admin.php\'>panneau d\'admin</a> sont admin/admin',1,'hi','Hello')");
q("create table t(i int(9) not null auto_increment,f varchar(99),primary key(i), unique key f(f))engine=InnoDB default charset=utf8 auto_increment=2");
q("insert into t values(1,'post.php')");
q("create table u(i int(9)not null auto_increment,n text not null,p text not null,primary key(i))engine=InnoDB DEFAULT charset=utf8 auto_increment=2");
q("insert into u values(1,'admin','21232f297a57a5a743894a0e4a801fc3')");
header('location:hi/');
s:
session_start();
$p=$_GET["p"];
$q=q("select p.c,p.t,t.f from p,t where p.p=t.i and p.n='$p'");
$c=array();
while($f=mysql_fetch_array($q))
	$c[]=$f;
if(!$c[0])
	l(array("404.php"),array("c"=>"Page $p non trouvée","t"=>"Oups..."));
$c=$c[0];
$p=array();
if($c["f"]!==null)
	$p[]=$c["f"];
$p[]="base.php";
l($p,$c);