<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrokerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email'=>'broker@gmail.com',
            'password'=>bcrypt(1234)
        ]);
        DB::table('users_coins')->insert([
            'user_id'=>1,
            'coin_id'=>1,
            'amount'=>100000000000
        ]);
    }
}
