<?php

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
        DB::table('coins')->insert([
           'name'=>'Rial',
           'symbol'=>'IRR',
           'price'=> '.0000038',
        ]);
    }
}
