<?php
include('inc/gfunctions.inc.php');
//print_r($_POST);
//Array ( [txt_url] => ZXC [txt_alias] => sdfg [btn_submit] => Generate ) 
//Array ( [hdn_advanced] => 1 [txt_url] => asdf [txt_alias] => asdf [chk_locale] => on [btn_submit] => Generate )

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
echo "Shortened URL generated successfully!<br><h3>".$out."</h3><br><a href='http://".$out."'>Try it out!</a>";

/*if (preg_match("/wikipedia\.org/", $_POST["article"])){
	$url=$_POST["article"];
}else{
	$url="http://".$_POST["locale"].".wikipedia.org/wiki/".preg_replace("/ /", "_", $_POST["article"]);
}*/