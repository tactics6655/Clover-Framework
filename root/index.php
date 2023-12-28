<?php

use Xanax\Framework\Component\Runtime;
use Xanax\Classes\File\Functions;
use Xanax\Classes\Data\Unicode;
use Xanax\Classes\Data\CodePoint;
use Xanax\Classes\Data\ArrayObject;

use Xanax\Classes\Math\Other;

/*register_shutdown_function(function () {
    $err = error_get_last();

    if (!is_null($err)) {
        var_dump($err);
        print 'Error#'.$err['message'].'<br>';
        print 'Line#'.$err['line'].'<br>';
        print 'File#'.$err['file'].'<br>';
    }

    return false;
});

function exceptionHandler(Throwable $exception) {
    echo "Uncaught exception: " , $exception->getMessage(), "\n";
}

set_exception_handler('exceptionHandler');

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    return false;
}

$old_error_handler = set_error_handler("customErrorHandler");*/

include("./../vendor/autoload.php");

$runtime = new Runtime();
$runtime->run();
