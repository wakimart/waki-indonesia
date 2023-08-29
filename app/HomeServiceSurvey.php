<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeServiceSurvey extends Model
{
    protected $fillable = [
    	'home_service_id', 'result_quest_1', 'result_quest_2', 'result_quest_3', 'result_quest_4', 'online_signature'
    ];

    public static $allQuest = [
    	"Apakah Anda merasa puas dengan kualitas layanan yang diberikan oleh tim kami di rumah Anda ?",
    	"Bagaimana penilaian Anda terhadap keramahan tim kami yang datang ke rumah Anda ?",
    	"Apakah tim kami bersedia menjawab pertanyaan atau kekhawatiran Anda dengan baik ?",
    	"Apakah tim kami tepat waktu sesuai dengan janji yang telah dijadwalkan ?",
    ];

    public function homeService(){
    	return $this->belongsTo('App\HomeService');
    }

    public function resultAverage(){
    	$res1 = $this->result_quest_1 * 4 / 20; //point 4
    	$res2 = $this->result_quest_2 / 5; //point 1
    	$res3 = $this->result_quest_3 * 2 / 10; //point 2
    	$res4 = $this->result_quest_4 * 3 / 15; //point 3

    	$conclussion = ($res1+$res2+$res3+$res4) / 4;
    	return floor($conclussion * 5);
    }
}