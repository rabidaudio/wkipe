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
	$('#loading').show();
	//replaces the submit action with a jQuery $.post() command, so we can have more control
	$.post('generate.php',$('#frm_generate').serialize(), function(data){
			//alert(data);
			$('#dia_generate').html(data);
			$('#btn_tryit').button();
			$('#btn_clipboard').button();
			$('#dia_generate').dialog("open");
		}
	);
	$('#loading').hide();
}

function clipit(){
	selectText('txt_short');
	$('#btn_clipboard').val('Now Ctrl-C');
	$('#btn_clipboard').button( "disable" );
}

//This lovely little guy comes from Jason Delman, jasonedelman.com via
//http://stackoverflow.com/questions/985272/jquery-selecting-text-in-an-element-akin-to-highlighting-with-your-mouse
function selectText(element) {
    var doc = document
        , text = doc.getElementById(element)
        , range, selection
    ;    
    if (doc.body.createTextRange) { //ms
        range = doc.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) { //all others
        selection = window.getSelection();        
        range = doc.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}
