<?php
if (!$_GET['page']){
	die();
}
$page = $_GET['page'];
if (!$_GET['lang']){
	$lang=http_get("lang.php");//TODO this aint right
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
