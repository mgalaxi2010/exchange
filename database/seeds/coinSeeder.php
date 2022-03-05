<?php

use App\Models\Coin;
use Carbon\Carbon;
use GuzzleHttp\Client;
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
        $httpClient = new Client();
        $dollar_api = config('api.navasan.usdt') . '&api_key=' . config('api.navasan.api_key');
        $dollar = $httpClient->get($dollar_api);
        $dollar_response = json_decode($dollar->getBody()->getContents(), true);

        DB::table('coins')->insert([
           'name'=>'Rial',
           'symbol'=>'IRR',
           'price'=> 1 / ($dollar_response['usdt']['value'] * 10),
            'created_at' => Carbon::now()
        ]);

    }
}
