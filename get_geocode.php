<?php

//$arr1 = geocode("149 9th St, San Francisco, CA, 94103", true);
//echo $arr1;

function geocode($address, $toGeocode){
    $ch = curl_init();
    $full_address = curl_escape($ch, $address);

    $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/$full_address.json?access_token=pk.eyJ1Ijoicm9sbGluZ2tleWJvYXJkIiwiYSI6ImNrbzYzc2Y3ejB3bzMybnF3OHV6OWZ4eW8ifQ.3RDjEPUiKBkQgE2Cj98hzw";

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    $geo_json = curl_exec($ch);
    curl_close($ch);

    $geo_array = json_decode($geo_json, true);
    if (!empty($geo_array)) {
        if ($toGeocode)
            return $geo_array['features'][0]['geometry']['coordinates'];
        else{
            return $geo_array['features'][0]['place_name'];
        }
    }
}

function calcDistance($address1, $address2){

}