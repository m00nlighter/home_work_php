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
$geocoding -> json();//json type of request
//$geocoding -> xml();//xml type of request
$geocoding->apiKey('AIzaSyByx6q61twWq8bjZyR78AndRVurzFpiqQQ');//not requered
//$geocoding->country("germany");
$geocoding->geocode("berlin");
echo $geocoding->get("formatted_address");
$geocoding->next();
echo $geocoding->get("formatted_address");
echo "<hr/>";
$geocoding->place ("ChIJd8BlQ2BZwokRAFUEcm_qrcA");
$latlng = $geocoding->get("geometry.location");
$geocoding->result_type('street_address');
$geocoding->reverse($latlng);
echo $geocoding->get("formatted_address");
