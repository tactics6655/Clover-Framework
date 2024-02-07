<?php

declare(strict_types=1);

namespace Neko\Classes\Debug;

use Neko\Classes\Exception\Handler as ExceptionHandler;
use Neko\Classes\File\Functions as FileFunction;
use Neko\Classes\Reflection\Handler as ReflectionHandler;
use Neko\Classes\HTML\Handler as HTMLHandler;

use ReflectionParameter;
use ReflectionType;
use ReflectionMethod;

class ErrorHandler
{
    public static function register()
    {
        $handler = new Static();

        ExceptionHandler::setExceptionHandler(array($handler, 'handleException'));
    }

    private function compile($arguments)
    {
        return FileFunction::getInterpretedContent((__DIR__ . '/../../Template/Exception.php'), $arguments);
    }

    public function handleException(\Throwable $e)
    {
        $code = $e->getCode();
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $traces = $e->getTrace();
        $className = get_class($e);

        $traces = ReflectionHandler::parseTrace($traces);

        $arguments = [
            'code' => $code,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'traces' => $traces,
            'className' => $className
        ];

        //echo 0 ?!("1"?! ( ""?""(""):!""?!"1"??"2":"C"?!"":""?""??""?!""??"2":"C"??"2":"C"??"2") :"C"??"2"):"C";
        
        //echo 0 ?!0xFF??0xCF:0x89;

        //echo (0 ?!(0?!(((99)??0??99??0)):99):'CC');
        //echo 0 ?!0xCF??0x89:0xFF;

        echo $this->compile($arguments);
    }

}