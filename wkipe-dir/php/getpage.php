<?php
include('functions.inc.php');


//for ajax friendly web crawlers, like so:
//	https://developers.google.com/webmasters/ajax-crawling/docs/getting-started
if (preg_match("/^_escaped_fragment_\=/", $_SERVER['QUERY_STRING'])){
	//echo $_SERVER['QUERY_STRING']."<-->";
	$page = preg_replace("/^_escaped_fragment_\=\//", "", $_SERVER['QUERY_STRING']);
	$index = file_get_contents('../../index.html');
	$lang = handle_lang();
	//echo $page;
	$file_requested ='../pages/'.$page.".";
	if (!file_exists($file_requested.$lang)){
		$file_requested=$file_requested."en";
	}else{
		$file_requested=$file_requested.$lang;
	}
	$file = file_get_contents($file_requested);
	//echo $file;
	die(str_replace('<div id="inner_page"></div>', '<div id="inner_page">'.$file.'</div>', $index));
}

function print_file($page, $lang){
	$file='../pages/'.$page.".";
	if (!file_exists($file.$lang)){
		@readfile($file."en");
	}else{
		@readfile($file.$lang);
	}
}

if (!$_GET['page']){
	die();
}
$page = $_GET['page'];
if (!$_GET['lang']){
	$lang=handle_lang();
}else{
	$lang=$_GET['lang'];
}
print_file($page, $lang);
?>
