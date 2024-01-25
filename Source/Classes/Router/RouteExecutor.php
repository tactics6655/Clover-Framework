<?php

declare(strict_types=1);

namespace Xanax\Classes\Router;

use Xanax\Classes\Reflection\Handler as ReflectionHandler;
use Xanax\Classes\DependencyInjection\Container;

class RouteExecutor
{
    private $class;
    private $method;
    private $callback;

    private $arguments;

    private Container $container;

    public function __construct($class, $method, $callback, $arguments, Container $container)
    {
        $this->class = $class;
        $this->method = $method;
        $this->callback = $callback;
        $this->arguments = $arguments;
        $this->container = $container;
    }

    public function __invoke()
    {
        $className = $this->class;
        $callback = $this->callback;
        $methodName = $this->method;

        if (isset($className) && !empty($className) && class_exists($className)) {
            $callback = new $className;
        }

        if (!isset($methodName) && empty($methodName)) {
            return ReflectionHandler::callMethodArray($callback, ($this->arguments ?? array()));
        }

        if (is_object($callback)) {
            return ReflectionHandler::invoke($callback, ($this->arguments ?? array()), $this->container, $methodName);
        }
    }
}
