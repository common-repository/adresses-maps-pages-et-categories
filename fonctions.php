<?php
if (!function_exists('convertirAdresseGeocodeur')) {
 function convertirAdresseGeocodeur($address){

	$address = str_replace(" ", "%", $address);
	// On prépare l'URL du géocodeur

	if ( get_option( 'cleAPIGoogleMaps2' ) !== false ) {
	$geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=FR&key=".get_option( 'cleAPIGoogleMaps2' );
	}else{
	  $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=FR";
	}
	

	$url_address = utf8_encode($address);
	$url_address = urlencode($url_address);

	$query = sprintf($geocoder,$url_address);
	$results = file_get_contents($query);
	$results = json_decode($results);
	
	//echo "<pre>"; print_r($results);echo "</pre>";

	$location = $results->results[0]->geometry->location;
	$adresseComplet = $results->results[0]->formatted_address;

	$array = array($location,$adresseComplet);

	return $array;
 }
}