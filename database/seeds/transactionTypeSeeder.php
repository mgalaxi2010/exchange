<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class transactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->insert([
            'title'=>'deposit'
        ]);
        DB::table('transaction_types')->insert([
            'title'=>'withdrawal'
        ]);
    }
}
