<?php

// Load the autoloader first to ensure all classes and dependencies are available
require_once '../autoload.php';

// Load the Dotenv class and set environment variables
require_once '../app/Libs/Dotenv.php';
App\Libs\Dotenv::load('../.env');

// Load other necessary files
require_once '../config/database.php';
require_once '../app/Libs/Helpers.php';

App\Libs\Route::loadMiddlewareConfig('../config/middleware.php');
require_once '../routes/web.php';
App\Libs\Route::dispatch();
