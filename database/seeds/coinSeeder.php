<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class coinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coins')->insert([
            'name' => 'Rial',
            'symbol' => 'IRR',
            'created_at' => Carbon::now()
        ]);

        DB::table('coins')->insert([
            'name' => 'Usdt',
            'symbol' => 'USDT',
            'created_at' => Carbon::now()
        ]);

        DB::table('coins')->insert([
            'name' => 'Bitcoin',
            'symbol' => 'Btc',
            'created_at' => Carbon::now()
        ]);

        DB::table('coins')->insert([
            'name' => 'Ethereum',
            'symbol' => 'Eth',
            'created_at' => Carbon::now()
        ]);

        DB::table('coins')->insert([
            'name' => 'Shiba Inu',
            'symbol' => 'Shib',
            'created_at' => Carbon::now()
        ]);

    }
}
