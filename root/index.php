<?php

use Xanax\Framework\Component\Runtime;
use Xanax\Classes\HTTP\Router;

include("./../vendor/autoload.php");

$router = new Router();
$response = $router->get('/test', function () {
    return 'test007788';
})
->handle();

echo $response;