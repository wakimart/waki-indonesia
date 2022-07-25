<?php

use App\Bank;
use Illuminate\Database\Seeder;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = ['1' => 'BCA', '2' => 'BNI', '3' => 'MEGA', '4' => 'HSBC', '5' => 'MANDIRI', '6' => 'DANAMON', '7' => 'CITIBANK', '8' => 'CIMB NIAGA', '9' => 'MAYBANK', '10' => 'OCBC', '11' => 'PANIN BANK', '12' => 'PERMATA BANK', '13' => 'STANDARD CHATER', '14' => 'BUKOPIN', '15' => 'BLIBLI.COM', '16' => 'Bank Jateng', '17' => 'TUNAI'];
        foreach ($banks as $key => $bank) {
            Bank::create([
                'id' => $key,
                'name' => $bank
            ]);
        }
    }
}
