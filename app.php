<?php
#******************************************************************#
#                      GA-Spider Application                       #
#******************************************************************#
# Google Analytics — Beacons for tracking users                    #
# Application for tracking users through pictures and proxy links  #
# Copyright (c) 2016 Vasilyuk Vasiliy <vasilyuk.vasiliy@gmail.com> #
#******************************************************************#
#                   GitHub->https://git.io/vrsil                   #
#******************************************************************#
# PHP application has been tested on version 5.5                   #
#******************************************************************#


# Defining constants
// define();
define('GA_SSL_URL', 'https://ssl.google-analytics.com/collect');
define('SES_COOKIE', '__SID');
define('SES_LIFETIME', 2*365*24*60*60); // 2 Year
define('RED_URL', 'https://git.io/vrsil'); // Redirect url from bad request
define('DEFAULT_PRINT_IMG_TYPE', 'png'); // or gif, or jpg
define('IMG_CONF_JSON','img/config.json');


# Configuration for environment
// ini_set();
ini_set('session.name', SES_COOKIE);
ini_set('session.gc_maxlifetime', SES_LIFETIME);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1000);
ini_set('session.use_strict_mode', true);
ini_set('session.use_cookies', true);
ini_set('session.use_only_cookies', true);
ini_set('session.cookie_lifetime', SES_LIFETIME);
ini_set('session.cookie_secure', false);
ini_set('session.cookie_httponly', true);
ini_set('session.cache_limiter', 'nocache');
ini_set('session.hash_function', 'whirlpool');
ini_set('session.hash_bits_per_character', 6);



# Configuration headers
// header();
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: ' . date('r'));



# Main block
if($_SERVER['REQUEST_METHOD']!='GET'){
	header('HTTP/1.1 405 Method Not Allowed');
	die('Method Not Allowed');
} $_POST=NULL; $_REQUEST=NULL; $r = false;
$_GET = array_change_key_case($_GET, CASE_LOWER); // All key 
session_start(); // Session start


// Favicon.ico
if($_SERVER['REQUEST_URI']=='/favicon.ico'){
	if(file_exists('img/favicon.ico')){
		header('Content-type: image/x-icon');
		readfile('img/favicon.ico');
	} else {
		printDefaultImg();
	}
	exit();
}


// Tracking
$cheak = stripos($_SERVER['REQUEST_URI'], '/t');
if($cheak !== false && $cheak == 0){
	if( !empty($_GET['gtid']) ) gaDataSend(); // If is set Google Tracking ID
	if( !empty($_GET['go']) ){
		$url = urlValidator($_GET['go']);
		if($url){ header('Location: '.$url); exit();}
			else header('Location: '.RED_URL);
	}
	checkAndPrintImgList();
	exit();
}


// If an invalid request redirects users
if(!$r){
	header('HTTP/1.1 301 Moved Permanently'); // Header redirect
	header('Location: '.RED_URL); // Redirect to git repo
	exit();
}



# Functions block
// Validate and return image
function checkAndPrintImgList(){
	// check image print type
	$typeList=array('png','jpg','gif','svg'); // List image types
	foreach($typeList as $v){
		if(array_key_exists($v, $_GET)){
			$type = $v;
			break;
		} else {
			$type = DEFAULT_PRINT_IMG_TYPE; // Default type;
		}
	}
	
	
	$imgList = array();
	if(file_exists(IMG_CONF_JSON)){
		$imgList = json_decode(file_get_contents(IMG_CONF_JSON), true);
		$imgList = array_change_key_case($imgList, CASE_LOWER);
		// check image name
		foreach($imgList as $k => $v){
			if(array_key_exists($k, $_GET)){
				$img = $v; break;
			}
		}
		if(isset($img)){
			return checkAndPrintImg($img.$type);
		}
	}
	return printDefaultImg($type);
}


// Cheack and return images
function checkAndPrintImg($path){
	if(!file_exists($path)) printDefaultImg($ext);
	$ext = end(explode('.', $path));
	if($ext == 'jpg' || $ext == 'jpeg') header('Content-type: image/jpeg');
	elseif($ext == 'png') header('Content-type: image/png');
	elseif($ext == 'svg') header('Content-type: image/svg+xml');
	elseif($ext == 'gif') header('Content-type: image/gif');
	else printDefaultImg();	
	return readfile($path);
}


// Gives the user the default image, if other image not found
function printDefaultImg($e = DEFAULT_PRINT_IMG_TYPE){
	if( $e == 'jpg' ){
		header('Content-type: image/jpeg');
		// ATTENTION! DO NOT CHANGE!
		echo base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACv/EABgQAQEBAQEAAAAAAAAAAAAAAAYFBAMC/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AAPrFip6qSunSVA0bNEFlYxYrLNFGqWKkdHTYQpUqGxp2V7qC7X2a6lmzU16qNSjq07t2nvp79evoP/Z=');
	} elseif( $e == 'gif' ){
		header('Content-type: image/gif');
		// ATTENTION! DO NOT CHANGE!
		echo base64_decode('R0lGODlhAQABAIAAAP// /wAAACwAAAAAAQABAAACAkQBADs=');
	} else {
		header('Content-type: image/png');
		// ATTENTION! DO NOT CHANGE!
		echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVR42mNgAAIAAAUAAen63NgAAAAASUVORK5CYII=');
	}
	return true;
}


// User Language
function httpUserLang(){
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		preg_match_all('/([a-zA-Z]{2}-[a-zA-Z]{2})|([a-z]{2})/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang);
		if(isset($lang[0][0])) return $lang[0][0];
	} return false;
}


// Random integer value
function randInt(){
	$bytes = openssl_random_pseudo_bytes(100);
  $hex = bin2hex($bytes);
	return base_convert(hash_hmac('md5', 
		json_encode($_SERVER).rand(-PHP_INT_MAX,PHP_INT_MAX).$hex, 
			hash('gost',rand(-PHP_INT_MAX,PHP_INT_MAX).$hex)), 16,10);
}


// Client IP
function httpUserIp(){
	if(getenv('HTTP_CLIENT_IP'))
		$ip = getenv('HTTP_CLIENT_IP');
	elseif(getenv('HTTP_X_FORWARDED_FOR'))
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	elseif(getenv('HTTP_X_FORWARDED'))
		$ip = getenv('HTTP_X_FORWARDED');
	elseif(getenv('HTTP_FORWARDED_FOR'))
		$ip = getenv('HTTP_FORWARDED_FOR');
	elseif(getenv('HTTP_FORWARDED'))
		$ip = getenv('HTTP_FORWARDED');
	elseif(getenv('REMOTE_ADDR'))
		$ip = getenv('REMOTE_ADDR');
	else $ip = false;
	return $ip;
}


// GO / Validate url
function urlValidator($url){
	$url = preg_grep("/((https?|ftp):\/\/(\S*?\.\S*?))([\s)\[\]{},;\"\':<]|\.\s|$)/i", explode("\n", $url));
	if($url) return $url[0]; else return false;
}

function gtidValidator($id){
	$id = preg_grep("/^UA-[0-9]{1,}-[0-9]{1,}$/i", explode("\n", $id));
	if($id) return $id[0]; else return false;
}


function gaDataSend(){
	// gtid — Google Tracking ID
	if( !gtidValidator($_GET['gtid']) ) return false;
	if( isset($_SERVER['HTTP_USER_AGENT']) ) $gAReq['ua']=$_SERVER['HTTP_USER_AGENT']; // User agent
	if( httpUserLang() ) $gAReq['ul']=httpUserLang(); // User lang
	if( isset($_SERVER['HTTP_REFERER']) ) $gAReq['dr']=$_SERVER['HTTP_REFERER']; // Document Referrer
	if( httpUserIp() ) $gAReq['uip']=httpUserIp(); // User IP

	
	// Request uri
	preg_match("/^([^?]*)?.*$/", $_SERVER['REQUEST_URI'], $uri);
	if($uri[1] == '') $uri[1] = '/';
	$gAReq['dp'] = trim($uri[1]); // Request uri
	if(substr($gAReq['dp'], -1) != '/') $gAReq['dp'] .= '/';
	
	
	// Use referer / request uri +@+ referer / mr — mark referer
	if( isset($_SERVER['HTTP_REFERER'], $_GET['mr']) ){
		if(substr($gAReq['dp'], -1) != '/') $gAReq['dp'] .= '/';
		$uri = $_SERVER['HTTP_REFERER'];
		$uri = str_replace(array('http://', 'https://'),'',$uri);
		$gAReq['dp'].='(REFERER)/'.$uri;
	}$uri == null;
	
	
	// Use mark / mgo — mark go address
	if(isset($_GET['mgo']) && urlValidator($_GET['go'])){
		$uri = urlValidator($_GET['go']);
		if(substr($gAReq['dp'], -1) != '/') $gAReq['dp'] .= '/';
		$gAReq['dp'].='(GOADDRESS)/'.$uri; $uri == null;
	}
	
	
	// Set static GA data
	$gAReq['v']=1; // The Protocol version. The current value is '1'.
	$gAReq['t']='pageview'; // Hit type
	$gAReq['ds']='web' ; // Indicates the data source of the hit.
	$gAReq['qt']=700; // Transmission data delay ms
	$gAReq['cid']=$_COOKIE[SES_COOKIE]; // Client ID
	$gAReq['sc']='start'; // Used to control the session duration.
	$gAReq['tid']=$_GET['gtid']; // Google Tracking ID
	$gAReq['z']=randInt(); // Random integer / clear cache


	// Generate query string
	$gAReqSrt = GA_SSL_URL.'?'.http_build_query($gAReq);
	// Send data to GA
	file_get_contents($gAReqSrt);
	return true;
}