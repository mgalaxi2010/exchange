<?php

use Carbon\Carbon;
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
        $dollarPrice = \App\helpers\helper::getDollarPrice();
        $coins = \App\helpers\helper::getCoinPrices();
        DB::table('coins')->insert([
            'name' => 'Rial',
            'symbol' => 'IRR',
            'price' => 1 / ($dollarPrice * 10),
            'created_at' => Carbon::now()
        ]);
        DB::table('coins')->insert([
            'name' => 'Usdt',
            'symbol' => 'USDT',
            'price' => 1,
            'created_at' => Carbon::now()
        ]);
        foreach ($coins as $coin) {
            DB::table('coins')->insert([
                'name' => $coin['name'],
                'symbol' => $coin['symbol'],
                'price' => $coin['current_price'],
                'created_at' => Carbon::now()
            ]);
        }
    }
}
