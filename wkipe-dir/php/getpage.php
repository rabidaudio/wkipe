<?php
if (!$_GET['page']){
	die();
}
$page = $_GET['page'];
if (!$_GET['lang']){
	$lang=http_get("lang.php");
}else{
	$lang=$_GET['lang'];
}

@readfile('../pages/'.$page.".".$lang);
