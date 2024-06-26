<?php

declare(strict_types=1);

namespace Clover\Classes\Routing;

use Clover\Classes\Reflection\Handler as ReflectionHandler;
use Clover\Classes\DependencyInjection\Container;

use Closure;
use Clover\Classes\Data\ArrayObject;

class RouteExecutor
{
    private string $class;

    private string $method;

    private mixed $callback;

    /** @var string[] */
    private $arguments;

    private Container $container;

    public function __construct(string $class, string $method, mixed $callback, $arguments, Container $container)
    {
        $this->class = $class;
        $this->method = $method;
        $this->callback = $callback;
        $this->arguments = $arguments;
        $this->container = $container;
    }

    private function getContainer()
    {
        return $this->container;
    }

    private function getCallback()
    {
        return $this->callback;
    }

    private function getMethod()
    {
        return $this->method;
    }

    private function getClass()
    {
        return $this->class;
    }

    private function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Call a callback from this route
     * 
     * @return mixed
     */
    public function __invoke(mixed $nextArguments = []): mixed
    {
        $class = $this->getClass();
        $callback = $this->getCallback();
        $method = $this->getMethod();
        $arguments = new ArrayObject();
        $arguments = $this->getArguments() ?? [];
        $arguments = [...$arguments, $nextArguments];
        $container = $this->getContainer();

        if (isset($class) && !empty($class) && class_exists($class)) {
            $callback = new ($class);
        }

        if (!isset($method) && empty($method)) {
            return ReflectionHandler::callMethodArray($callback, $arguments);
        }

        if (is_object($callback)) {
            return ReflectionHandler::invoke($callback, $method, $arguments, $container);
        }

        return false;
    }
}
