<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Utils extends Model
{
	//eng = english, id = indonesia
    public static $lang = "eng";
    public static function sendSms($destination, $text){
        $headers = [
            'Authorization' => 'Bearer x6qcavYzC2AEVj93s7YJmpMUoIGluiLyfqvKWuXIRU',
            'Content-Type' => 'application/json',
        ];
        $client = new \GuzzleHttp\Client();

        $body = '{ "source": "Wakimart", "destination": "'.$destination.'", "text": "'.$text.'", "encoding": "AUTO" }';

        $res = $client->post('https://api.wavecell.com/sms/v1/WAKimart_9eEBB_hq/single', [
            'headers' =>  $headers,
            'body' => $body
            ]);
    }
}
