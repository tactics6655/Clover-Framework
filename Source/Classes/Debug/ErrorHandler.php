<?php

declare(strict_types=1);

namespace Clover\Classes\Debug;

use Exception;
use Clover\Classes\Exception\Handler as ExceptionHandler;
use Clover\Classes\File\Functions as FileFunction;
use Clover\Classes\Reflection\Handler as ReflectionHandler;

class ErrorHandler
{
    public static function register()
    {
        $handler = new static();

        ExceptionHandler::setExceptionHandler([$handler, 'handleException']);
        ExceptionHandler::setErrorHandler([$handler, 'handleError']);
        ExceptionHandler::registerShutdownFunction([$handler, 'handleShutdown']);
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

        switch ($errorRaised) {
            case E_ERROR: //1
                $content = $this->compile(__DIR__ . '/../../Template/Error.php', $arguments);
                exit($content);
                break;
            case E_WARNING: // 2
            case E_PARSE: //4
            case E_NOTICE: //8
            case E_CORE_WARNING: //32
            case E_DEPRECATED: //8192
            case E_STRICT: // 2048
            case E_USER_ERROR: //256
            default:
                break;
        }
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

        echo $this->compile(__DIR__ . '/../../Template/Exception.php', $arguments);
    }
}
