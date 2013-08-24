<?php
include('db.inc.php');
$alphabet = 'abcdefghijkmnopqrstuvxyz23456789';
 

 function normal_log($article, $locale, $ip_addr){
 	$host=gethostbyaddr($ip_addr);
 	mysql_query("insert into normal_log (`article`, `locale`, `ip_addr`, `host`, `timestamp`) values (\"$article\", \"$locale\", \"$ip_addr\", \"$host\", NOW())") or die("could not log: ".mysql_error());
}

 function custom_log($custom, $locale, $ip_addr){
 	$host=gethostbyaddr($ip_addr);
 	mysql_query("insert into custom_log (`custom_url`, `locale`, `ip_addr`, `host`, `timestamp`) values (\"$custom\", \"$locale\", \"$ip_addr\", \"$host\", NOW())") or die("could not log: ".mysql_error());
}

function custom_lookup($cust_id, $article){
	$query = sprintf("select * from custom_url where string like '%s' and custom_id=%d", mysql_real_escape_string($article), mysql_real_escape_string($cust_id));
	$results=mysql_query($query);
	return mysql_fetch_array($results);
}

function generate_normal($art) {
	//remove any illegal characters
	//replace spaces with underscores
	//remove any other whitespace
	//make sure the string isn't empty
	//then return wki.pe url
	//echo "<p>$art becomes ";
	$art=preg_replace('/^https?:\/\//','',$art);
	$art=preg_replace('/.*?wikipedia.org\/wiki\//','',$art);
	$art=preg_replace('/ /','_', $art);
	$art=stripslashes($art);
	//echo "$art</p>";
	return "wki.pe/".$art;
	//return $art;
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
	$art=preg_replace('/ /','_', $art);
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




#these aren't perfect, but they will do for now. TODO make better encode/decode algorithms
function base_decode($char){
	global $alphabet;
	$len = strlen($alphabet);
	$sray = array_reverse(str_split($char));
	foreach($sray as $key => $letter){
		if ($key==0){
			$num=strpos($alphabet,$letter);
		}else{
			$num+=(strpos($alphabet,$letter))*pow($len,$key);
		}
	}
	return $num;
}
function base_encode($num){
	global $alphabet;
	$len = strlen($alphabet);
	if ($num==0){
		return substr($alphabet, $num, 1);
	}
	while($num>0){
		$rem=$num % $len;
		$num = floor($num / $len);
		$code=substr($alphabet, $rem,1).$code;
	}
	return $code;
}


 //returns the language code from the client if there is a wikipeida server
 //in that language. otherwise returns 'en'
 function get_lang_code(){
	//$locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
	//$language = locale_get_primary_language($locale);
	$language = getDefaultLanguage();
	switch ($language){
		case "cv":
		case "el":
		case "es":
		case "en":
		case "fi":
		case "fr":
		case "gl":
		case "it":
		case "ja":
		case "ko":
		case "lt":
		case "hv":
		case "mk":
		case "nn":
		case "no":
		case "sl":
		case "sv":
		case "ta":
		case "zh":
			return $language;
			break;
		default:
			return "en";
	}
}



#########################################################
# Copyright © 2008 Darrin Yeager                        #
# http://www.dyeager.org/                               #
# Licensed under BSD license.                           #
#   http://www.dyeager.org/downloads/license-bsd.txt    #
#########################################################
#http://www.dyeager.org/blog/2008/10/getting-browser-default-language-php.html
function getDefaultLanguage() {
   if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
      return parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
   else
      return parseDefaultLanguage(NULL);
   }

function parseDefaultLanguage($http_accept, $deflang = "en") {
   if(isset($http_accept) && strlen($http_accept) > 1)  {
      # Split possible languages into array
      $x = explode(",",$http_accept);
      foreach ($x as $val) {
         #check for q-value and create associative array. No q-value means 1 by rule
         if(preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i",$val,$matches))
            $lang[$matches[1]] = (float)$matches[2];
         else
            $lang[$val] = 1.0;
      }

      #return default language (highest q-value)
      $qval = 0.0;
      foreach ($lang as $key => $value) {
         if ($value > $qval) {
            $qval = (float)$value;
            $deflang = $key;
         }
      }
   }
   return strtolower($deflang);
}
