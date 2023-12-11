<?php
use Xanax\CMS\Component\Runtime;
use Xanax\Classes\HTTP\Router;
use Xanax\Classes\HTML\Handler as HTMLHandler;

include("./../vendor/autoload.php");

$runtime = new Runtime();

Router::Get("/", function () {
    return "test";
});

$response = Router::Run();

