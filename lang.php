<?php
#language detection, picker, and storer
include('inc/functions.inc.php');

if (!$_COOKIE['lang']){
	$lang=get_lang_code();
	setcookie('lang',$lang,time()+60*60*24*14, "wki.pe");
	echo $lang;
}else{
	echo $_COOKIE['lang'];
}

#Now we definately have the language.
/*
Over .4 million articles:
en
nl
de
sv
fr
it
es
ru
pl
ja
vi
pt
war
ceb
zh
uk
ca
*/
