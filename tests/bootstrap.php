<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    echo "Loading '/config/bootstrap.php'...";
    require dirname(__DIR__).'/config/bootstrap.php';
} else {
    // This is to ensure we don't use .env in production
    if (!isset($_SERVER['APP_ENV'])) {
        if (!class_exists(Dotenv::class)) {
            throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
        }
    } elseif ('dev' === $_SERVER['APP_ENV']) {
        echo "\n*****\n*****\n Bootstrapping Symfony tests in a '" . $_SERVER['APP_ENV'] . "' environment. The recommended way is to start from a 'test' environment... \n*****\n*****\n" . PHP_EOL;
    }

    if (method_exists(Dotenv::class, 'bootEnv')) {
        (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
    } elseif (method_exists(Dotenv::class, 'loadEnv')) {
        (new Dotenv())->loadEnv(dirname(__DIR__).'/.env');
    }
}
