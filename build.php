<?php

// Jalankan file ni selepas set nilai env dan pra-deploy
require_once __DIR__ . '/App/loader.php';

$root = __DIR__;
$env = $root . '/.env';
$js = $root . '/awam/js/helper/constant.js';

try {
    loadJsConstants($js, $env);
} catch (Exception $error) {
    echo $error->getMessage();
    exit(404);
}