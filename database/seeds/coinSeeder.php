<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
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

        DB::table('coins')->insert([
            'name' => 'Rial',
            'symbol' => 'IRR',
            'price' => 1 / ($dollarPrice * 10),
            'created_at' => Carbon::now()
        ]);

    }
}
