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

    'coinConvertValidation'=>[
        'eth'=>[
            'max'=>5,
            'min'=>0.0004
        ],
        'btc'=>[
            'max'=>0.5,
            'min'=>0.00025
        ],
        "usdt"=>[
            'max'=>10000,
            'min'=>10
        ],
        "shib"=>[
            'max'=>40000000,
            'min'=>400000
        ],
        "irr"=>[
            'max'=>500000000,
            'min'=>500000
        ]
    ]
];
