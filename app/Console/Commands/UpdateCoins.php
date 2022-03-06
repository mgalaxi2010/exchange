<?php

namespace App\Console\Commands;

use App\Models\Coin;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCoins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:coins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get coins price via api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $httpClient = new Client();
        $coin_api = config('api.coinGecko.market') . '?' . config('api.coinGecko.coins');
        $dollar_api = config('api.navasan.usdt') . '&api_key=' . config('api.navasan.api_key');
        $coins = $httpClient->get($coin_api);
        $dollar = $httpClient->get($dollar_api);
        $coins_response = json_decode($coins->getBody()->getContents(), true);
        $dollar_response = json_decode($dollar->getBody()->getContents(), true);
        $dollarPrice = $dollar_response['usdt']['value'] * 10;

        Coin::updateOrCreate(['name' => 'Rial', 'symbol' => 'IRR'], ['price' => 1 / $dollarPrice, 'created_at' => Carbon::now()]);

        Coin::updateOrCreate(['name' => 'Usdt', 'symbol' => 'USDT'], ['price' => 1, 'created_at' => Carbon::now()]);

        foreach ($coins_response as $coin) {
            Coin::updateOrCreate(['name' => $coin['name']], ['symbol' => strtoupper($coin['symbol']), 'price' => $coin['current_price'], 'created_at' => Carbon::now()]);
        }

    }
}
