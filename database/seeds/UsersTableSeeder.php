<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
                'username'  => 'admin',
		        'name'  => 'admin',
		        'email' => 'wakimart.id@gmail.com',
		        'password'  => bcrypt('wakimart2019')
		]);
    }
}
