<?php
include('db.inc.php');
$alphabet = 'abcdefghijkmnopqrstuvxyz23456789';

/**
 * This is an includable file for functions that can be called. It is almost completely based
 * on functions.php from php-url-shortener 1.0.1, which is released under GPLv3 and is available from
 * Google Code: http://code.google.com/p/php-url-shortener/ Note that the coding functionality has been
 * removed and rewitten for our purposes, and several functions have been added and removed. An important
 * thanks to the original author:
 *
 * @author    Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link      http://kevin.vanzonneveld.net/
 * 
 * The following are the functions available and their arguments and returns:
 
 * New functions:
 get_lang_code()
 article_prefetch()
 
 
 * From php-url-shortener:
 
 get_uri() returns the uri requested
 selfHost() returns the domain name of the host
 dbconnect() connects to SQL database
 
 * See the bottom for the original comments from php-url-shortener.
 **/
 
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

#these aren't perfect, but they will do for now.
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











//get the article beforehand, make sure it exists. if it does, don't
//do anything special. if it doesn't (i.e. returns a 404), then tell
//the system
/*function article_prefetch($arturl){
	//one option is to use system(wget)
	//another would be to figure out httprequest
	//neither of these are great
	//nevermind! use curl -L :D
	//requres libcurl
	$ch = curl_init($arturl);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$returned =curl_exec($ch);
	//do some checking to see if it returns the fake page or the real page
	//the page should include the test string if it doesn't exist
	$teststring= "<b>Wikipedia does not have an article with this exact name.</b>"
	//return TRUE; //if valid url
	//return FALSE; //if invalid url (this means 'Special:Search' is needed
}*/


/*
// To obtains requested URI
function get_uri(){
	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		// IIS Mod-Rewrite
		if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
		}else if(isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			// IIS-Isapi_Rewrite
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
		}else{
			// Use ORIG_PATH_INFO, if there is no PATH_INFO
			if (!isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO'])){
				$_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
			}
			// Check duplicated
			if (isset($_SERVER['PATH_INFO']) ) {
				if ($_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME']){
					$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
				}else{
					$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
				}
			}
			// Append the query string if it exists and isn't null
			if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
	}
	return $_SERVER['REQUEST_URI'];
}

// Get self host name
function selfHost() {
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$pt = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($pt, 0, strpos($pt, "/")).$s;
	return $protocol."://".$_SERVER['SERVER_NAME'];
}
	



// Add the URL to the DB
function add_url_to_db($url){
	// Check if the address it's alredy compressed and return it if it is
	$url = trim($url);
	$sql = 'SELECT * FROM '.S_TB_URLS.' WHERE url = "'.mysql_real_escape_string($url).'"';
	if($row = db_squery($sql)){
		return alphaID($row['id']);
	}else{
		// let's add that address
		$sql = 'INSERT INTO '.S_TB_URLS.' (url) VALUES("'.mysql_real_escape_string($url).'")';
		mysql_query($sql);
		$url_nid = mysql_insert_id();
		return alphaID($url_nid);
	}
}*/

// Try to add the URL, return the ID or false
/*function add_url($url){
	// Check if the URL has a HOST
	if($host = check_domain($url)){			
		// Connect to the DB
		db_connect();
		// Check if the URL has a not banned Host
		if(check_banned_domain($host)){
			return false;
		}else{
			return add_url_to_db($url);
		}
		// Close the DB
		mysql_close();
	}else{
		return false;
	}
}

// give a HTTP 404 error and include any page
function error_404($r){
	header('HTTP/1.0 404 Not Found');
	die('Error:'.$r);  
}*/

/**
 * Translates a number to a short alhanumeric version
 * 
 * SOURCE:
 * http://kevin.vanzonneveld.net/techblog/article/create_short_ids_with_php_like_youtube_or_tinyurl/
 *
 * Translated any number up to 9007199254740992
 * to a shorter version in letters e.g.:
 * 9007199254740989 --> PpQXn7COf
 *
 * specifiying the second argument true, it will
 * translate back e.g.:
 * PpQXn7COf --> 9007199254740989
 *
 * this function is based on any2dec && dec2any by
 * fragmer[at]mail[dot]ru
 * see: http://nl3.php.net/manual/en/function.base-convert.php#52450
 *
 * If you want the alphaID to be at least 3 letter long, use the
 * $pad_up = 3 argument
 *
 * In most cases this is better than totally random ID generators
 * because this can easily avoid duplicate ID's.
 * For example if you correlate the alpha ID to an auto incrementing ID
 * in your database, you're done.
 *
 * The reverse is done because it makes it slightly more cryptic,
 * but it also makes it easier to spread lots of IDs in different
 * directories on your filesystem. Example:
 * $part1 = substr($alpha_id,0,1);
 * $part2 = substr($alpha_id,1,1);
 * $part3 = substr($alpha_id,2,strlen($alpha_id));
 * $destindir = "/".$part1."/".$part2."/".$part3;
 * // by reversing, directories are more evenly spread out. The
 * // first 26 directories already occupy 26 main levels
 *
 * more info on limitation:
 * - http://blade.nagaokaut.ac.jp/cgi-bin/scat.rb/ruby/ruby-talk/165372
 *
 * if you really need this for bigger numbers you probably have to look
 * at things like: http://theserverpages.com/php/manual/en/ref.bc.php
 * or: http://theserverpages.com/php/manual/en/ref.gmp.php
 * but I haven't really dugg into this. If you have more info on those
 * matters feel free to leave a comment.
 * 
 * @author    Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link      http://kevin.vanzonneveld.net/
 * 
 * @param mixed   $in     String or long input to translate     
 * @param boolean $to_num Reverses translation when true
 * @param mixed   $pad_up Number or boolean padds the result up to a specified length
 * 
 * @return mixed string or long
 */
?>