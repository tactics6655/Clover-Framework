<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP\Router;

use COM;
use Xanax\Classes\Reflection\Handler as ReflectionHandler;

class Route
{
    private \Closure | string $callback;

    private array $middlewares = array();

    private string $pattern;

    private array $arguments;

    public function __construct($pattern, $callback)
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    public function setMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function handle($container, $dependencyInjection = false)
    {
        $className = null;
        $methodName = null;
        $callback = $this->getCallback();

        if (!is_callable($callback) && is_string($callback)) {
            $statiMethodArguments = explode('::', $this->callback);
            $className = $statiMethodArguments[0];
            $methodName = $statiMethodArguments[1];
        }

        if (isset($className) && !empty($className) && class_exists($className)) {
            $callback = new $className;
        }

        if (!isset($methodName) && empty($methodName)) {
            return ReflectionHandler::callMethodArray($callback, ($this->arguments ?? array()));
        }

        if (is_object($callback)) {
            if ($dependencyInjection) {
                return ReflectionHandler::invoke($callback, $methodName, ($this->arguments ?? array()), $container);
            } else {
                return ReflectionHandler::callClassMethod($callback, $methodName, ($this->arguments ?? array()));
            }
        }

        return false;
    }

    private function getCallback()
    {
        return $this->callback;
    }

    private function isEmptyPattern()
    {
        return empty($this->pattern);
    }

    private function getPattern()
    {
        return $this->pattern;
    }

    public function match($urlSegments)
    {
        if ($this->isEmptyPattern()) {
            return false;
        }

        $separatedSegments = explode('/', trim($this->getPattern() ?? "", '/'));

        if (count($separatedSegments) <= 0) {
            return false;
        }

        for ($z = 0; $z < count($separatedSegments); $z++) {
            $segment = $urlSegments[$z];
            $routeSegment = $separatedSegments[$z];

            if (preg_match('/^({\w*})$/', $routeSegment, $match)) {
                $this->arguments[] = $segment;

                continue;
            }

            if ($routeSegment != $segment) {
                return false;
            }
        }

        return true;
    }
}
