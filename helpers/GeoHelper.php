<?php
function geocode($address){
    $address = urlencode($address);
    $url = "https://maps.google.com/maps/api/geocode/json?address={$address}&key=AIzaSyDIAQzYuYP9w52_aQ7IvlNMLJebm1SItkA";
    $resp_json = file_get_contents($url);
    $resp = json_decode($resp_json, true);
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
        if($lati && $longi && $formatted_address){
            $data_arr = array();            
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
            return $data_arr;
        }else{
            return false;
        }
    }else{
        return false;
    }
}