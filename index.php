<?
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
/*
	Home work php
	all code and extensions written by Kulik Vadim
			:)	:)	:)	:)	:)


*/
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
require_once('extensions/arrayhelper/ArrayHelper.php');
require_once('extensions/filecache/FileCache.php');
require_once('extensions/getpage/GetPage.php');
require_once('extensions/geocoding/Geocoding.php');

$geocoding = new Geocoding();
$geocoding -> json();//json
$geocoding->key('AIzaSyByx6q61twWq8bjZyR78AndRVurzFpiqQQ');//not requered
//$geocoding->country("germany");//filters functions route, locality, administrative_area , postal_code , country
$geocoding->administrative_area("Berlin");
//$geometry['location'] = $geocoding->get("berlin",'geometry.location');
//var_dump($geometry);
var_dump($geocoding->get("berlin",'formatted_address'));
echo "<br/>";
var_dump($geocoding->next()); //first, next, prev, last, result /// function can be called only after get function