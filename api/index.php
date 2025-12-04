<?php

// Test 1: Apakah PHP jalan?
echo "PHP Works\n";

// Test 2: Apakah autoload jalan?
require __DIR__ . '/../vendor/autoload.php';
echo "Autoload Works\n";

// Test 3: Apakah bootstrap jalan?
$app = require_once __DIR__ . '/../bootstrap/app.php';
echo "Bootstrap Works\n";

// Test 4: Apakah request handling jalan?
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);