<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

if (isset($_ENV['BOOTSTRAP_RESET_DATABASE']) && $_ENV['BOOTSTRAP_RESET_DATABASE']) {
    echo 'Environment : '.$_ENV['APP_ENV'].PHP_EOL.PHP_EOL;
    echo 'Drop old DB if exists...';
    exec('php bin/console doctrine:mongodb:schema:drop --ansi');
    echo 'OK'.PHP_EOL;
    echo 'Create DB schema...';
    exec('php bin/console doctrine:mongodb:schema:create');
    echo 'OK'.PHP_EOL;
    echo 'Update DB schema...';
    exec('php bin/console --env=test doctrine:mongodb:schema:update');
    echo 'OK'.PHP_EOL;
}
