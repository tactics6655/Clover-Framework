<?php

declare(strict_types=1);

namespace Neko\Classes\Debug;

use Exception;
use Neko\Classes\Exception\Handler as ExceptionHandler;
use Neko\Classes\File\Functions as FileFunction;
use Neko\Classes\Reflection\Handler as ReflectionHandler;

class ErrorHandler
{
    public static function register()
    {
        $handler = new static();

        ExceptionHandler::setExceptionHandler(array($handler, 'handleException'));
        ExceptionHandler::setErrorHandler(array($handler, 'handleError'));
        ExceptionHandler::registerShutdownFunction(array($handler, 'handleShutdown'));
    }

    public function handleShutdown()
    {
        $lastError = error_get_last();
        if ($lastError == null) return;

        $content = $this->compile(__DIR__ . '/../../Template/Shutdown.php', $lastError);
        exit($content);
    }

    public function handleError($errorRaised, $errorMessage, $fileName, $lineNumber)
    {
        $arguments = [
            'error_raised' => $errorRaised,
            'error_message' => $errorMessage,
            'filename' => $fileName,
            'linenumber' => $lineNumber
        ];

        $content = $this->compile(__DIR__ . '/../../Template/Error.php', $arguments);
        exit($content);
    }

    private function compile($path, ?array $arguments)
    {
        return FileFunction::getInterpretedContent($path, $arguments);
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

        echo $this->compile(__DIR__ . '/../../Template/Exception.php', $arguments);
    }
}
