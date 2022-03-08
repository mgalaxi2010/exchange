<?php

return [

    'coinGecko' => [
        'market' => env('COIN_GECKO_MARKETS', 'https://api.coingecko.com/api/v3/coins/markets'),
        'coins' => env('COIN_GECKO_FILTERED_COINS', 'vs_currency=usd&ids=bitcoin,ethereum,shiba-inu')
    ],
    'navasan' => [
        'usdt' => env('USDT_NAVASAN_API', 'http://api.navasan.tech/latest/?item=usdt'),
        'api_key' => env('USDT_NAVASAN_API_KEY', 'free2kBGVAyqEwG8K20JzAOdwexPgeUU'),
    ],

    'min_coin_price'=>[
        'IRR'=>500000,
        'USDT'=>10,
        'BTC'=>0.0001,
        'ETH'=>0.002,
        'SHIB'=>50000,
    ]
];
