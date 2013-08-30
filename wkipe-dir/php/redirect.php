<?php
include('functions.inc.php');

#STEP 1: if no (or bad) arguments, redirect to homepage
#STEP 2: identify and generate correct redirect location
#STEP 3: execute and log

$url=$_SERVER['REQUEST_URI'];
if (preg_match('/400.shtml/',$_SERVER['REDIRECT_QUERY_STRING'])) {
	$query = preg_replace('/^\//', '', $_SERVER['REQUEST_URI']);
}else{
	$query = $_SERVER['REDIRECT_QUERY_STRING'];
}
if (!$_COOKIE['lang']){
	//$locale = getDefaultLanguage();
	$language = get_lang_code();
}else{
	$language = $_COOKIE['lang'];
}

$ip_addr = $_SERVER['REMOTE_ADDR'];

$querya=explode("|",$query);
$article=$querya[0];
$custom_id=$querya[1];
#print_r($_SERVER);
#echo "<br>this is with unicode encoding:".mb_convert_encoding($_SERVER['REQUEST_URI'], 'UTF-8');
#echo "<br>requested $custom_id.wki.pe$url redirects to redirect.php?$query \n Your IP address is $ip_addr. We found your locale: $locale and recommend $language.wikipedia.org \n the article requested is $article.\n\n";
if ($custom_id==NULL){
#	echo "<br>We should redirect to $article_clean";
	normal_log($article, $language, $ip_addr);
	$article_clean=rawurlencode($article);
}else{
	$cust_dec = base_decode($custom_id);
#	echo "<br>We should redirect to the custom url number $cust_dec for $article_clean";
	$results = custom_lookup($cust_dec, $article);
	if($results['article']==NULL){
		die("We couldn't find what you were looking for!");
	}
	if($results['locale']){
		$language=$results['locale'];
#		echo "language switched to $language";
	}
	$article=$results['article'];
	custom_log($results['custom_url_id'], $language, $ip_addr);
	$article_clean=$article;
}
$article_clean=strip_tags($article_clean); //XSS attack protection. this should have been done before the database, but just to be sure...
$redirect = "http://$language.wikipedia.org/wiki/Special:Search/$article_clean";
#echo "<br>in conclusion, we think $redirect is the right place to go. $article_clean";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>wki.pe - Working...</title>
<meta http-equiv="REFRESH" content=
	<?php echo "0;url=".$redirect; ?>
>
<script type="text/javascript">
/* <![CDATA[ */
    (function() {
        var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
        s.type = 'text/javascript';
        s.async = true;
        s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
        t.parentNode.insertBefore(s, t);
    })();
/* ]]> */</script>
</HEAD>
<BODY>
<p><h3>We are redirecting you to the article requested.</h3></p>
<p>If the page doesn't load, click <a href="<?php echo $redirect; ?>">HERE</a>.</p>
<p>
<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://wki.pe"></a>
</p>
</BODY>
</HTML>
