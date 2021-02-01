<?php

namespace App\Console\Commands;

use App\User;
use App\HomeService;
use Illuminate\Console\Command;

class notif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'homeservice:notif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sent notif 2 hour before appointment homeservice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    function sendFCM($body){
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: key=AAAAfcgwZss:APA91bGg7XK9XjDvLLqR36mKsC-HwEx_l5FPGXDE3bKiysfZ2yzUKczNcAuKED6VCQ619Q8l55yVh4VQyyH2yyzwIJoVajaK4t3TJV-x-4f_a9WUzIcnOYzixPIUB5DeuWRIAh1v8Yld',
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        $result = curl_exec($curl);
        $this->info($result);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($curl));
            $this->info(curl_error($curl));
        }
        curl_close($curl);
        return $result;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dateStart = new \DateTime();
        $dateEnd = new \DateTime();
        $dateStart = $dateStart->modify('+1 hours');
        $dateEnd = $dateEnd->modify('+2 hours');
        $formatted_dateStart = $dateStart->format('Y-m-d H:i:s');
        $formatted_dateEnd = $dateEnd->format('Y-m-d H:i:s');
        $this->info($formatted_dateStart);
        $homeservices = HomeService::where('active', true)->limit(1)->get();   //->whereBetween('appointment', [$formatted_dateStart, $formatted_dateEnd])
        foreach($homeservices as $homeservice){
            $body = ['registration_ids'=>($homeservice->getToken()['fmc_token']),
             'collapse_key'=>"type_a",
             "notification" => [
                "body" => "Pada ".$homeservice->appointment." Di rumah ".$homeservice->name." Alamat ".$homeservice->address,
                "title" => "Jadwal Home Service 2",
                "icon" => "ic_launcher"
              ],
             'data'=> $homeservice];
             $body = json_encode($body);
             $this->sendFCM($body);
            $this->info(json_encode($homeservice->getToken()['fmc_token'])); 
        }
        $this->info('List Home Service :'.count($homeservices));
    }
}
