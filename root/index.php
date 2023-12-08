<?php

include("./../vendor/autoload.php");

use Xanax\CMS\Component\Runtime;
use Xanax\Classes\File\Functions as FileFunctions;
use Xanax\Annotation\Route;

use ReflectionClass;
use ReflectionMethod;
use Reflector;

$runtime = new Runtime();

class TTT
{
    public function __construct()
    {
        echo print_r($this);
    }
}

echo PHP_MAJOR_VERSION;

$ttt = new TTT();
$class_names = FileFunctions::getClassName("controller.php");
foreach ($class_names as $class_name)
{
    $class = new ReflectionClass($class_name);

    foreach ($class->getMethods() as $method) {
        if ($method->isStatic() ||
            $method->isPrivate() ||
            $method->isProtected()) {
            continue;
        }

        $attributes = $method->getAttributes(Route::class);

        echo print_r($attributes);
    }

    $route = $class->getAttributes(Route::class);

    echo print_r($route[0]->getArguments());
}