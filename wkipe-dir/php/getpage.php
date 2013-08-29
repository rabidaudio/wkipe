<?php
include('functions.inc.php');
if (!$_GET['page']){
	die();
}
$page = $_GET['page'];
if (!$_GET['lang']){
	$lang=handle_lang();
}else{
	$lang=$_GET['lang'];
}
$file='../pages/'.$page.".";
if (!file_exists($file.$lang)){
	@readfile($file."en");
}else{
	@readfile($file.$lang);
}
?>
