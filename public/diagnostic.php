<?php

try {
    // Set error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
    define('LARAVEL_START', microtime(true));

    // Check if maintenance mode file exists
    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        echo "In maintenance mode\n";
        require $maintenance;
    }

    // Register the Composer autoloader
    echo "Loading composer autoloader...\n";
    require __DIR__.'/../vendor/autoload.php';
    echo "Composer autoloader loaded successfully\n";

    // Bootstrap Laravel
    echo "Bootstrapping Laravel...\n";
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "Laravel bootstrapped successfully\n";

    // Try to handle request
    echo "Handling request...\n";
    $app->handleRequest(request: Illuminate\Http\Request::capture());
    echo "Request handled successfully\n";
    
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
}

?>
