<?php

namespace App\helpers;

use GuzzleHttp\Client;

class helper
{

    public static function getDollarPrice()
    {
        $httpClient = new Client();
        $dollar_api = config('api.navasan.usdt') . '&api_key=' . config('api.navasan.api_key');
        $dollar = $httpClient->get($dollar_api);
        $dollar_response = json_decode($dollar->getBody()->getContents(), true);
        return $dollar_response['usdt']['value'];
    }

    public static function getCoinPrices()
    {
        $httpClient = new Client();
        $coin_api = config('api.coinGecko.market') . '?' . config('api.coinGecko.coins');
        $coins = $httpClient->get($coin_api);
        return json_decode($coins->getBody()->getContents(), true);
    }
}
