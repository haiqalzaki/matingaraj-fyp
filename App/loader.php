<?php

// function to load ENV (param = env_filepath)
function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception(".env file not found: " . $file);
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (preg_match('#^(["\'])(.*)\\1$#', $value, $matches)) {
                $value = $matches[2];
            }

            $_ENV[$key] = $value;
        }
    }
}

// function to generate js constants (param = js_filepath | env_filepath)
function loadJsConstants($file, $env) 
{    
    if ($env && file_exists($env)) {
        loadEnv($env);
    }

    $base = $_ENV['HOME_URL'] ?? '';
    $home = $_ENV['HOME_URL'] ?? '';

    $jsLine = "// HANYA CONSTANT KONFIGURASI DISINI //\n\n";
    $jsLine .= "const BASE_PATH = `" . $base . "`;\n";
    $jsLine .= "const REDIRECT_HOME = `" . $home . "`;\n";

    if (!file_put_contents($file, $jsLine)) 
    {
        throw new Exception("Failed creating Javascript constant: " . $file);
    }
}
