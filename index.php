<html>
<head>
<title>wki.pe - A URL shortener for Wikipedia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script type="text/javascript" src="jquery-1.10.1.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.10.3.custom.min.js"></script>
  <link rel="stylesheet" href="css/mw.css" />
  <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.3.custom.min.css" />
<script type="text/javascript">
$(document).ready(function() {
	$( "input[type=submit], input[type=button], button" ).button();
	$( '#dia_generate' ).dialog({modal: true, autoOpen: false});
	$( '#sec_alias' ).hide();
	$( '#hdn_advanced' ).val('0');
	state_update();
	$('#txt_url, #txt_alias').keyup(state_update);

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
	state_update();
}

function state_update(){
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

function generateURL(){
	//replaces the submit action with a jQuery $.post() command, so we can have more control
	$.post('generate.php',$('#frm_generate').serialize(), function(data){
			//alert(data);
			$('#dia_generate').html(data);
			$('#btn_tryit').button();
			$('#dia_generate').dialog("open");
		}
	);
}

/* SO THIS ONLY WORKS FOR OLD IE, which is buggy as shit with jQuery UI to the point of being unusable.


function clipit(){
	copyToClipboardCrossbrowser( $('#txt_short').text() );
}

//TRYING TO DO THIS WITHOUT FUCKING FLASH
//http://stackoverflow.com/questions/7713182/copy-to-clipboard-for-all-browsers-using-javascript
function copyToClipboardCrossbrowser(s) {  
	alert("called: "+s);         
        //s = document.getElementById(s).value;               

        if( window.clipboardData && clipboardData.setData )
        {
            clipboardData.setData("Text", s);
        }           
        else
        {
            // You have to sign the code to enable this or allow the action in about:config by changing
            //user_pref("signed.applets.codebase_principal_support", true);
            netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');

            var clip = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
            if (!clip) return;

            // create a transferable

            var trans = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
            if (!trans) return;

            // specify the data we wish to handle. Plaintext in this case.
            trans.addDataFlavor('text/unicode');

            // To get the data from the transferable we need two new objects
            var str = new Object();
            var len = new Object();

            var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);

            str.data= s;        

            trans.setTransferData("text/unicode",str, str.data.length * 2);

            var clipid=Components.interfaces.nsIClipboard;              
            if (!clip) return false;
            clip.setData(trans,null,clipid.kGlobalClipboard);      
        }
    }*/
</script>
<script type="text/javascript">
//This is Flattr business

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
<div id="dia_generate" title="Shortener"><h3>Wait a minute!</h3><p>I'm not sure what article to use.</p></div>
<div id="content">
<p><a href="http://wki.pe/"><img src="wkipe-logo.png" alt="Wki.pe" style="width:250px"></a>
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
<input id="btn_submit" type="button" name="btn_submit" onClick="generateURL();" value="Generate">
<!--<input id="btn_submit" type="submit" name="btn_submit" value="Generate">-->
</fieldset>
</form>
</p>
<br/><br/>
<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://wki.pe"></a>
</div>
<div id="footer">
<a href="index.php">Home</a> | <a href=faq.html>FAQ</a> | Contact | <a href="legal.html">Legal</a>
</div>
</body>
</html>
