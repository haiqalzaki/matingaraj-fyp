<?php

declare(strict_types=1);

use App\Core\Router;

if (!session_id()) {
    session_start();
}

$root = dirname(__DIR__);
$env = $root . '/.env';

require_once $root . '/App/init.php';
require_once $root . '/App/vendor/autoload.php';

try {
    loadEnv($env);
} catch (Exception $error) {
    echo $error->getMessage();
    exit(404);
}

$logPath = $_ENV['BASE_ROOT'] . "/log";

if (!file_exists($logPath)) {
    mkdir($logPath, 0775, true);
}

spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace("\\", '/', $class) .".php";

    if (file_exists($path)) {
	require $path;
    } else {
	die("Autoload failed: File not found!" . $path);
    }
});

date_default_timezone_set('Asia/Kuala_Lumpur');

$route = new Router();
