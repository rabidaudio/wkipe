<html>
<head>
<title>wki.pe - A URL shortener for Wikipedia</title>
<script type="text/javascript" src="jquery-1.10.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#sec_alias').hide();
	$('#hdn_advanced').val('0');
});

function showAdvanced(){
	$('#sec_alias').show('slow');
	$('#btn_alias').hide('slow');
	$('#hdn_advanced').val('1');
}
</script>
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
</head>
<body>
<h1>Welcome</h1><p/>
This is where the generator goes. It needs some fields and a POST script. We also need a brief discription. <p/>
Then we need a link to the <a href=faq.html>FAQ PAGE</a><p/>
And finally copyright (or rather COPYLEFT) info and privacy policy.
<br>
<form id="frm_generate" name="frm_generate" action="generate.php" method="post">
<input id="hdn_advanced" type="hidden" name="hdn_advanced" value="">
Paste the url of the wikipedia page to shorten:<br>
<input id="txt_url" type="text" name="txt_url"><br>
<div id="sec_alias">
Shorthand alias:<br>
<input id="txt_alias" type="text" name="txt_alias"><br>
<input id="chk_locale" type="checkbox" name="chk_locale">Use my language
</div>
<input id="btn_alias" type="button" name="btn_alias" onClick="showAdvanced();" value="Add Aliasing">
<input id="btn_submit" type="submit" name="btn_submit" value="Generate">
</form>
<p>
<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://wki.pe"></a>
</p>
</body>
</html>