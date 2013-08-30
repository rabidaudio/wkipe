<?php
include('functions.inc.php');
//generate.php is a POST resource for the main site. For a GET resource, use api.php

//print_r($_POST); 
//echo gettype($_POST['aliased']);//string

/* changed $_POST variables to make more sense:
	article 	=> string (full URL or name)
	lang		=> string (wikipedia language version character code, NULL means use clicker's language)
	api		=> string ("true" or "false") (should we return HTML or JSON?)
	aliased		=> string ("true" or "false") (do we need to make it an aliased link?)
	alias		=> string (optional)	
*/


//called from the site, or by api?
if ($_GET['article']){
	include('api.php');
	die();
}


if (!$_POST['article']){
	die("<p>You must include the destination URL. <a href=\"index\" dest=\"local\">Click here</a> to try again.</p>");
}
if ($_POST['aliased']==="true" and $_POST['alias']===""){
	die("<p>You must include an alias, or generate a normal link. <a href=\"index\" dest=\"local\">Click here</a> to try again.</p>");
}

/*TODO replace with security token to ensure origin is actually wki.pe or API key if neccessary
if (!$_POST['btn_submit']){
	die("<p>You need to use <a href=\"index.php\">this page</a> to generate your shortened URLs.</p>");
}*/

//PROTECT AGAINST XSS
$txt_url=strip_tags($_POST['article']);
if ($txt_url !== $_POST['article']){
	echo "<p>Warning: we found HTML tags in your link and removed them for security reasons. If you think this is an error, <a href=\"contact\" dest=\"local\">tell me</a>.</p>";
}

if ($_POST['aliased']==='true'){
	if ($_POST['lang']){
		$locale=$_POST['lang'];
		$out=generate_alias($txt_url, $_POST['alias'], $locale);
	}else{
		$out=generate_alias($txt_url, $_POST['alias']);
	}
}else{
	$out=generate_normal($txt_url);
}

echo "Shortened URL generated successfully!<h3><span id='txt_short'>".$out.'<span></h3><a id="btn_tryit" target="_blank" href=\'http://'.str_replace('%2F','/',rawurlencode($out)).'\'>Try it out!</a>';
echo '<input id="btn_clipboard" type="button" onclick="clipit()" value="Select for copying"/>';

?>
