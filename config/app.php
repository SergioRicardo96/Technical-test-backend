<?php

use App\Libs\Dotenv;

return [
    'name' =>  Dotenv::get('APP_NAME', 'Technical test'),
    'debug' => (bool) Dotenv::get('APP_DEBUG', false),
    'timezone' => 'America/Mexico_City',
    'locale' => 'en',
    'key' => Dotenv::get('APP_KEY')
];
