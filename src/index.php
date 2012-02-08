<?php 
function getTitle(){
	return $_SESSION[0];
}
function l($f,$c){
	foreach($f as $g)
		if(file_exists($g="tpl/".$g)){
			$_SESSION[1]=$c[1];
			$_SESSION[0]=$c[4];
			include $g;
			exit;
		}
}
mysql_connect($h,$u,$p);
mysql_select_db($b);
if(file_exists("header.php"))goto s;
/* ADD FILES HERE */
include "helper.php";
$c="create table";
$n=" int(9) not null auto_increment";
$v=" varchar(99)";
$e="))engine=InnoDB default charset=utf8 auto_increment=2";
$t=" text not null";
$p="primary key";
$i="insert into";
q("$c p(i$n,c text,p int(9),n$v,t$v,$p(i$e");
q("$i p values(1,'Installation de 4k réussie. Les logs du <a href=\'".absPath()."/admin.php\'>panneau d\'admin</a> sont admin/admin',1,'hi','Hello')");
q("$c t(i$n,f$v,$p(i), unique key f(f$e");
q("$i t values(1,'post.php')");
q("$c u(i$n,n$t,p$t,$p(i$e");
q("$i u values(1,'admin','21232f297a57a5a743894a0e4a801fc3')");
header("location:hi/");
goto g;
s:
include "helper.php";
g:
session_start();
$p=s($_GET["p"]);
$q=q("select * from p,t where p.p=t.i and p.n='$p'");
$c=array();
if($f=f($q))
	$c[]=$f;
if(!$c[0])
	l(array("404.php"),array(1=>"Page $p non trouvée",4=>"Oups..."));
$c=$c[0];
l(array($c[6],"base.php"),$c);