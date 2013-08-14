<?php
/**
 * This is a set of functions to generate shortened urls. There are 2 types:
 * Normal ones (easy)
 * Alias ones (more difficult)
 * 
 * Remember to INCLUDE this file on pages that use these functions!
 * Written by Charles Knight, charles@rabidaudio.com
**/
include('db.inc.php');
include('rfunctions.inc.php');
function generate_normal($art) {
	//remove any illegal characters
	//replace spaces with underscores
	//remove any other whitespace
	//make sure the string isn't empty
	//then return wki.pe url
	$art=preg_replace('/^https?:\/\//','',$art);
	$art=preg_replace('/.*?wikipedia.org\/wiki\//','',$art);
	return "wki.pe/".$art;
}
function generate_alias($art, $alias, $locale="NULL") {
	//remove any illegal characters
	//replace spaces with underscores
	//remove any other whitespace
	//make sure the string isn't empty
	
	//check database to see how many times $al has been used
	//increment this, convert it to a base-62 (upper,lower,nums)
	//add this info to database
	//then return wki.pe url
	
	$art=preg_replace('/^https?:\/\//','',$art);
	$art=preg_replace('/.*?wikipedia.org\/wiki\//','',$art);
	if ($locale != "NULL"){
		$locale="'".$locale."'";
	}
	$result=mysql_query("SELECT count(*), `custom_id` FROM `custom_url` WHERE `string` like \"".mysql_real_escape_string(strtolower($alias))."\" AND `article` like \"".mysql_real_escape_string($art)."\" AND `locale` like ".$locale.";");
	$result=mysql_fetch_array($result);
	if ($result[0]>0){//if it already exists
		return base_encode($result[1]).".wki.pe/".$alias;
	}
	//otherwise, add a new one
	$alnum=get_alias_count($alias);
	
	mysql_query("INSERT INTO `custom_url` (`string`, `article`, `custom_id`, `ip_addr`, `host`, `locale`, `timestamp`) VALUES ('".mysql_real_escape_string($alias)."', '".mysql_real_escape_string($art)."', '".$alnum."', '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."', '".mysql_real_escape_string(gethostbyaddr($_SERVER['REMOTE_ADDR']))."', ".$locale.", NOW());") or die("could not log: ".mysql_error());
	return base_encode($alnum).".wki.pe/".$alias;
}


function get_alias_count($alias){
	$result=mysql_query("SELECT count(*) FROM `custom_url` WHERE `string` like \"".mysql_real_escape_string(strtolower($alias))."\";");
	#print_r(mysql_fetch_array($result));
	$result=mysql_fetch_array($result);
	return $result[0];
}