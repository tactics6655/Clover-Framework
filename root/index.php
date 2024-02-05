<?php

use Neko\Framework\Component\Runtime;

include("./../vendor/autoload.php");

define('__ROOT__', __DIR__);

$runtime = new Runtime();
$runtime->run();
