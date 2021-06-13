<?php

use Illuminate\Database\Seeder;
use App\User;

class DummyUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            [
               'name'=>'Admin',
               'email'=>'admin@yopmail.com',
                'is_admin'=>'1',
                'user_role'=>'admin',                
               'password'=> bcrypt('07070707'),
            ],
            [
               'name'=>'Jhon User',
               'email'=>'reguser@yopmail.com',
                'is_admin'=>'0',
               'password'=> bcrypt('07070707'),
            ],
        ];
  
        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
    
}