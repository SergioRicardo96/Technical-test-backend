<?php

use App\Libs\Dotenv;

return [
    'connections' => [
        'mysql' => [
            'host' => Dotenv::get('DB_HOST', 'localhost'),
            'port' => Dotenv::get('DB_PORT', 3306),
            'username' => Dotenv::get('DB_USERNAME', 'root'),
            'password' => Dotenv::get('DB_PASSWORD', ''),
            'database' => Dotenv::get('DB_DATABASE', 'test_db'),
            'charset' => Dotenv::get('DB_CHARSET', 'utf8mb4'),
        ]
    ],
];
