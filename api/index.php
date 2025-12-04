<?php

define('LARAVEL_START', microtime(true));

// Set proper paths for serverless
$_ENV['APP_BASE_PATH'] = dirname(__DIR__);

// Register the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Set storage path for serverless
$app->useStoragePath($_ENV['APP_BASE_PATH'] . '/storage');

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);