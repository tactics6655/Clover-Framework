<?php

use Xanax\Framework\Component\Runtime;
use Xanax\Classes\HTTP\Router;

include("./../vendor/autoload.php");

$router = new Router();
$response = $router->get('/test', function () {
    return '123';
})
->setMiddleware(function (mixed $req, $next) {
    echo '123test';
    return $next();
}, function ($next) {
    $next();
})
->handle();

echo $response;