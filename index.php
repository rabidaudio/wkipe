<html>
<head>
<title>wki.pe - A URL shortener for Wikipedia</title>
  <script type="text/javascript" src="jquery-1.10.1.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.10.3.custom.min.js"></script>
  <link rel="stylesheet" href="css/mw.css" />
  <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.3.custom.min.css" />
<script type="text/javascript">
$(document).ready(function() {
	$( "input[type=submit], input[type=button], button" ).button();
	$('#sec_alias').hide();
	$('#hdn_advanced').val('0');
	funkitron();
	$('#txt_url, #txt_alias').keyup(funkitron);

	//so we want to grab the user's locale language #TODO add a language selector!
	// and then use this guy: http://en.wikipedia.org/w/api.php?action=opensearch&format=json&search=QUERY
	// to grab the list of articles to reccommend
	$('#txt_url').autocomplete({
		source: function(request, response){
			var searchstr=request.term;
			var lang="en";
			var url = "http://"+lang+".wikipedia.org/w/api.php";
			var qs = {
				action: 'opensearch',
				format: 'json',
				search: request.term
			}
			//$.getJSON(url+searchstr+"&callback=?", function(data) {
			$.getJSON(url+"?callback=?", qs, function(data) {
				response(data[1]);
			});
		}
	});

});

function showAdvanced(){
	$('#sec_alias').show('slow');
	$('#btn_alias').hide('slow');
	$('#hdn_advanced').val('1');
	funkitron();
}

function funkitron(){
	//if txt_url is a url, disable autofill
	var urlpat = new RegExp("^https?://");
	if( urlpat.test( $('#txt_url').val() ) ){
		$("#txt_url").autocomplete( "disable" );
	}

	//if the fields aren't filled in, disable the submit button
	if( ($('#txt_url').val().length > 0) && ( $('#hdn_advanced').val()=='0'
		|| ($('#hdn_advanced').val()=='1' && $('#txt_alias').val().length>0) )){
		//$('#btn_submit').prop("disabled",false);
		$('#btn_submit').button( "enable" );
	}else{
		//$('#btn_submit').prop("disabled",true);
		$('#btn_submit').button( "disable" );
	}	
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
<div id="content">
<p><img src="wkipe-logo.png" alt="Wki.pe" style="width:250px">
<div id="subtitle"><em>A URL shortener for Wikipedia articles</em></div>
</p>
<p>ADD DISCRIPTION
Then we need a link to the <a href=faq.html>FAQ PAGE</a><br/>
And finally copyright (or rather COPYLEFT) info and privacy policy.
</p>
<p>
<form id="frm_generate" name="frm_generate" action="generate.php" method="post">
<fieldset>
<input id="hdn_advanced" type="hidden" name="hdn_advanced" value="">
The name (or URL) of the Wikipedia article:<br>
<input id="txt_url" type="text" name="txt_url"><br>
<div id="sec_alias">
Shorthand alias:<br/>
<input id="txt_alias" type="text" name="txt_alias">
<p>
<input id="chk_locale" type="checkbox" name="chk_locale">Use my language
</p>
</div>
<input id="btn_alias" type="button" name="btn_alias" onClick="showAdvanced();" value="Add Aliasing">
<input id="btn_submit" type="submit" name="btn_submit" value="Generate">
</fieldset>
</form>
</p>
<br/><br/>
<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://wki.pe"></a>
</div>
<div id="footer">
<a href=faq.html>FAQ</a> | Contact | Legal
</div>
</body>
</html>
