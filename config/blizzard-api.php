<?php

// config for LeeMarkWood/BlizzardApi
return [
    'api_key' => env('BLIZZARD_API_KEY'),
    'api_secret' => env('BLIZZARD_API_SECRET'),
    'region' => env('BLIZZARD_API_REGION', 'eu'),
    'locale' => env('BLIZZARD_API_LOCALE', 'en_GB'),
];
