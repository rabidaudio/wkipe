<?php
include_once('functions.inc.php');

header('content-type: application/json; charset=utf-8');

//print_r($_GET); 
//echo gettype($_GET['aliased']);//string

/*
	article 	=> string (full URL or name)
	lang		=> string (wikipedia language version character code, NULL means use clicker's language)
	api		=> string ("true" or "false") (should we return HTML or JSON?)
	aliased		=> string ("true" or "false") (do we need to make it an aliased link?)
	alias		=> string (optional)	
*/

class API_response {
	public $error=false;
	public $error_num=array();
	public $error_message=array();
	public $url;

	function throw_my_error($num, $mesg, $die=NULL){
		$this->error=true;
		$this->error_num[]=$num;
		$this->error_message[]=$mesg;
		if ($die){
			die(stripslashes(json_encode($this)));
		}
	}
}

function handle_api_request(){
	$api_response= new API_response();

	//error checking
	/*API ERROR MESSAGES:
		false 	=> no error
		1 	=> Missing/Invalid URL
		2	=> Advanced URL doesn't have alias (only WARNING)
		3	=> Tags removed (only WARNING)
	*/
	if (!$_GET['article']){
		$api_response->throw_my_error(1,"Missing/Invalid URL", true);
	}
	if ($_GET['aliased']==="true" and ($_GET['alias']==="" or !$_GET['alias'])){
		$api_response->throw_my_error(2,"Aliased URL requested, but none supplied! Generating normal link instead");
		$_GET['aliased']="false";
	}

	/*TODO replace with security token to ensure origin is actually wki.pe or API key if neccessary
	if (!$_GET['btn_submit']){
		die("<p>You need to use <a href=\"index.php\">this page</a> to generate your shortened URLs.</p>");
	}*/

	//PROTECT AGAINST XSS
	$txt_url=strip_tags($_GET['article']);
	if ($txt_url !== $_GET['article']){
		$api_response->throw_my_error(3, "HTML tags detected and removed for security reasons.");
	}

	if ($_GET['aliased']==='true'){
		if ($_GET['lang']){
			$locale=$_GET['lang'];
			$out=generate_alias($txt_url, $_GET['alias'], $locale);
		}else{
			$out=generate_alias($txt_url, $_GET['alias']);
		}
	}else{
		$out=generate_normal($txt_url);
	}

	$api_response->url=$out;
	//return stripslashes(json_encode($api_response));
	return json_encode($api_response);
}
$json = handle_api_request();

if (isset($_GET['callback'])){
	echo "{$_GET['callback']}($json)";
}else{
	echo $json;
}
?>
