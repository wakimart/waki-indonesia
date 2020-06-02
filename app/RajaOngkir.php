<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RajaOngkir extends Model
{
    static public function FetchProvince()
    {
    	$curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
    			"key: de2e3798f92cf387377c64a70fdd2ff0"
			),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return (json_decode($response, true));
        }
    }

    static public function FetchCity($province){
    	$curl = curl_init();

    	curl_setopt_array($curl, array(
    		CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=".$province,
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_ENCODING => "",
    		CURLOPT_MAXREDIRS => 10,
    		CURLOPT_TIMEOUT => 30,
    		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    		CURLOPT_CUSTOMREQUEST => "GET",
    		CURLOPT_HTTPHEADER => array(
    			"key: de2e3798f92cf387377c64a70fdd2ff0"
    		),
    	));
    	$response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return (json_decode($response, true));
        }
    }
}
