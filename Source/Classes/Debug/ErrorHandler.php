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

        foreach ($traces as $key => &$trace) {
            if (!array_key_exists("class", $trace) || empty($trace)) {
                continue;
            }

            $reflectionClass = new \ReflectionClass($trace['class']);

            $methods = $reflectionClass->getMethods();

            $filtered = array_filter($methods, function ($method) use ($trace) {
                return $method->name == $trace['function'];
            });
            
            $trace['absolute_file_path'] = trim(str_replace(dirname(__ROOT__), '', $trace['file'] ?? ''), "/");
            $trace['full_string'] = sprintf("%s%s%s()", $trace['short_name'] ?? "", $trace['type'], $trace['function']);

            if (empty($filtered)) {
                continue;
            }

            $method = $reflectionClass->getMethod($trace['function']);

            $shortName = $reflectionClass->getShortName();
            $trace['short_name'] = $shortName;

            $arguments = "";

            if ($method->getNumberOfParameters() > 0) {
                $parameterArguments = [];
                $parameters = $method->getParameters();
                $methodArguments = [];
                
                /** @var ReflectionParameter|ReflectionType|null, mixed[] $parameters $parameters */
                foreach ($parameters as $parameterKey => &$parameter) {
                    if ($parameter === null) {
                        continue;
                    }

                    $parameterArguments[$parameterKey] = new \stdClass();

                    $values = [];

                    /* Type */
                    if ($parameter->hasType()) {
                        $type = $parameter->getType()->__toString();
                        $parameterArguments[$parameterKey]->parameter_type = $type;

                        $values[] = $type;
                    }

                    /* Name */
                    $name = $parameter->getName();
                    $parameterArguments[$parameterKey]->name = $parameter->getName();
                    $values[] = $name;

                    if ($parameter->isPassedByReference()) {
                    }

                    /* Default Value */
                    if ($parameter->isDefaultValueAvailable() && !isset($trace['args'][$parameterKey])) {
                        $defaultValue = $parameter->getDefaultValue();
                        $parameterArguments[$parameterKey]->default_value = $defaultValue;

                        $values[] = $defaultValue;
                    }
                    
                    /* Arguments */
                    if (isset($trace['args'][$parameterKey])) {
                        $argumentsData = [];
                        $arguments = $trace['args'][$parameterKey];

                        foreach (is_array($arguments) ? $arguments : [$arguments] as $argument) {
                            if (is_string($argument)) {
                                $argumentsData[] = $argument;
                            } else if (is_object($argument)) {
                                $argumentsData[] = get_class($argument) ?? ";;";
                            } else if (is_array($argument)) {
                                $argumentsData[] = "[".join(",", $argument)."]";
                            } else {
                                $argumentsData[] = "";
                            }
                        }

                        $joinArgument = join(', ', $argumentsData);

                        if (count($argumentsData) > 1) {
                            $joinArgument = "[" . $joinArgument . "]";
                        }

                        $values[] = $joinArgument;
                    }

                    $values = array_map(function ($value) {
                        if (is_array($value)) {
                            return "[".join(", ", $value)."]";
                        }

                        return $value;
                    }, $values);

                    $methodArguments[] = vsprintf((((!$parameter->hasType() ?!! "" : "%s "))."$%s = %s"), $values ?? []);
                }
                
                $arguments = join(', ', $methodArguments);

                $trace['arguments'] = $arguments;
                $trace['parameters'] = $parameterArguments;
            }

            if ($method->hasReturnType()) {
                $returnType = $method->getReturnType();
                $trace['return_type'] = $returnType;
            }

            $modifier = ReflectionHandler::getModifierString($method);
            
            $returnType = $trace['return_type'] ?? "";
            $trace['full_string'] = sprintf("%s %s%s%s(%s)".($returnType ? " : %s" : ""), $modifier, $trace['short_name'], $trace['type'], $trace['function'], $arguments ?? "", $returnType);
            
            $comment = $method->getDocComment();
            if (!$comment) {
                continue;
            }

            $comments = array_slice(explode("\n", $comment), 1);
            foreach ($comments as $key => &$comment) {
                $comment = $this->trimComment($comment);

                if (str_starts_with($comment, "@")) {
                    unset($comments[$key]);
                } else if (empty($comment)) {
                    unset($comments[$key]);
                }
            }

            $trace['comment'] = implode("<br/>", array_values($comments));
        }

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

    private function trimComment($comment)
    {
        $comment = trim($comment);
        $comment = rtrim($comment, "/");
        $comment = ltrim($comment, "*");
        $comment = trim($comment);

        return $comment;
    }
}