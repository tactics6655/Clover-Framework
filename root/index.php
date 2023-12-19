<?php

use Xanax\Framework\Component\Runtime;
use Xanax\Classes\File\Functions;
use Xanax\Classes\Data\Unicode;
use Xanax\Classes\Data\CodePoint;
use Xanax\Classes\Data\ArrayObject;

include("./../vendor/autoload.php");

$runtime = new Runtime();

$array = new ArrayObject(['a','b', 'c']);
$array = $array->reduce(function ($carry, $value) {
    return $carry . $value;
});

var_dump($array);