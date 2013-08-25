<?php
include('inc/functions.inc.php');
//print_r($_POST);
//Array ( [txt_url] => ZXC [txt_alias] => sdfg [btn_submit] => Generate ) 
//Array ( [hdn_advanced] => 1 [txt_url] => asdf [txt_alias] => asdf [chk_locale] => on [btn_submit] => Generate )

//error checking
if (!$_POST['txt_url']){
	die("<p>You must include the destination URL. <a href=\"index.php\">Click here</a> to try again.</p>");
}
if ($_POST['hdn_advanced']===1 and !$_POST['txt_alias']){
	die("<p>You must include an alias, or generate a normal link. <a href=\"index.php\">Click here</a> to try again.</p>");
}
/*TODO replace with security token to ensure origin is actually wki.pe
if (!$_POST['btn_submit']){
	die("<p>You need to use <a href=\"index.php\">this page</a> to generate your shortened URLs.</p>");
}*/

if ($_POST['hdn_advanced']==='1'){
	if ($_POST['chk_locale']==="on"){
		$locale=get_lang_code();
		$out=generate_alias($_POST['txt_url'], $_POST['txt_alias'], $locale);
	}else{
		$out=generate_alias($_POST['txt_url'], $_POST['txt_alias']);
	}
}else{
	$out=generate_normal($_POST['txt_url']);
}
//echo utf8_decode(rawurldecode($out))."<br>";
echo "Shortened URL generated successfully!<h3 id='txt_short'>".$out.'</h3><a id="btn_tryit" target="_blank" href=\'http://'.str_replace('%2F','/',rawurlencode($out)).'\'>Try it out!</a>';
//<input id="btn_clipboard" type="button" name="btn_clipboard" onClick="clipit();" value="Copy to Clipboard"/>

/*if (preg_match("/wikipedia\.org/", $_POST["article"])){
	$url=$_POST["article"];
}else{
	$url="http://".$_POST["locale"].".wikipedia.org/wiki/".preg_replace("/ /", "_", $_POST["article"]);
}*/
