<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NewNgrokUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ngrok:newngrokurl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get New Ngrok URL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ch = curl_init('https://api.ngrok.com/tunnels');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:"."Bearer 2R6F8Zbzsy9wCspLUZcyxZymDOc_2zYPzgNMw6vfg2U5Eq8Mf", "Ngrok-Version:2"));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response)->tunnels;
        if(sizeof($result) < 1){
            $data = [];
            $data['username'] = "WAKi Bot";
            $data['avatar_url'] = "https://waki-indonesia.co.id/sources/favicon.png";
            $data['content'] = "@everyone NGROK MATI !!!";
                            
            $ch = curl_init('https://discord.com/api/webhooks/1118761309173989397/kY5ga6bya6nab3bL9riKFijDz6v2cLIQUfCfnVqI6kvR3uWogzdT_LQENmlq7tGnS9Uq');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            return false;
        }

        $urlWakimartOffline = "Tidak Ada";
        $urlWakiOffline = "Tidak Ada";
        $urlFTP = "Tidak Ada";
        foreach ($result as $value) {
            if($value->forwards_to == "http://localhost:81"){
                $urlWakimartOffline = $value->public_url;
            }
            if($value->forwards_to == "https://localhost:443"){
                $urlWakiOffline = $value->public_url;
            }
            if($value->forwards_to == "localhost:22"){
                $urlFTP = $value->public_url;
            }
        }

        //send to discord
        $data = [];
        $data['username'] = "WAKi Bot";
        $data['avatar_url'] = "https://waki-indonesia.co.id/sources/favicon.png";
        $data['content'] = "@everyone \nWAKi offline New URL : ".$urlWakiOffline."\n"
                        ."WAKimart offline New URL : ".$urlWakimartOffline."\n"
                        ."WAKi offline FTP New URL : ".$urlFTP."\n";

        if(env('OFFLINE_URL') != $urlWakiOffline){
            $ch = curl_init('https://discord.com/api/webhooks/1118761309173989397/kY5ga6bya6nab3bL9riKFijDz6v2cLIQUfCfnVqI6kvR3uWogzdT_LQENmlq7tGnS9Uq');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        return true;
    }
}
