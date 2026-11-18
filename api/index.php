<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Di Vercel hanya /tmp yang bisa ditulis
$storage = '/tmp/storage';
foreach (['app/public', 'framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $dir) {
    if (! is_dir("$storage/$dir")) {
        mkdir("$storage/$dir", 0777, true);
    }
}

$env = [
    'APP_CONFIG_CACHE'     => '/tmp/config.php',
    'APP_EVENTS_CACHE'     => '/tmp/events.php',
    'APP_PACKAGES_CACHE'   => '/tmp/packages.php',
    'APP_ROUTES_CACHE'     => '/tmp/routes.php',
    'APP_SERVICES_CACHE'   => '/tmp/services.php',
    'LOG_CHANNEL'          => 'stderr',
    'LOG_STDERR_FORMATTER' => 'Monolog\Formatter\LineFormatter',
];
foreach ($env as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// Skrip ada di /api, tetapi aplikasi harus menganggap dirinya berada di akar (/)
// supaya alamat seperti /api/buku tidak kehilangan awalan /api
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($storage);
$app->handleRequest(Request::capture());