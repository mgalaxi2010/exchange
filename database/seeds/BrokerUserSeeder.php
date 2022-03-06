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
            'email'=>'broker@exchange.com',
            'password'=>bcrypt(1234)
        ]);
        DB::table('users_coins')->insert([
            'user_id'=>1,
            'coin_id'=>1,
            'amount'=> env('BROKER_WALLET_AMOUNT',100000000000)
        ]);

    }
}
