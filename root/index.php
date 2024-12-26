<?php

use Clover\Framework\Component\Runtime;

if (!file_exists("./../vendor/autoload.php")) {
    exit("Please run 'composer install' under application root directory");
}

include("./../vendor/autoload.php");

define('__ROOT__', __DIR__);

$runtime = new Runtime();
$runtime->run();