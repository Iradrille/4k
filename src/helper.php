<?php 
function r(){
	header('location:admin.php');exit();
}

function absPath(){
	$a=$_SERVER["PHP_SELF"];
	return substr($a,0,strlen($a)-10);
}

function i($l,$n,$v){
	echo "<p>$l<input type='text' name='$n' value='$v'/></p>";
}

function q($q){
	return mysql_query($q);
}

function u($x){
	return (isset($x["u"])&&strlen($x["u"]))?$x["u"]:str_replace(' ','-',$x["t"]);
}

function e($s){
	return str_replace('\'','\'\'',$s);
}

function f($q){
	return mysql_fetch_array($q);
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