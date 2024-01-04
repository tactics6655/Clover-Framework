<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP\Router;

use Xanax\Classes\Reflection\Handler as ReflectionHandler;

class Route
{
    private $callback;

    private $pattern;

    private $arguments;

    public function __construct($pattern, $callback)
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    public function handle($container)
    {
        $className = null;
        $methodName = null;
        $callback = $this->callback;

        if (!is_callable($this->callback) && is_string(($this->callback))) {
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
            return ReflectionHandler::invoke($callback, $methodName, ($this->arguments ?? array()), $container);
        }
    }

    public function match($urlSegments)
    {
        if (empty($this->pattern)) {
            return false;
        }

        $separatedSegments = explode('/', trim($this->pattern ?? "", '/'));

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
