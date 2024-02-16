<?php

declare(strict_types=1);

namespace Neko\Classes\Routing;

use Neko\Classes\Reflection\Handler as ReflectionHandler;
use Neko\Classes\DependencyInjection\Container;

use Closure;

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

    /**
     * Call a callback from this route
     * 
     * @return mixed
     */
    public function __invoke(mixed $next_arguments = [])
    {
        $class = $this->class;
        $callback = $this->callback;
        $method = $this->method;
        $arguments = $this->arguments ?? [];
        $container = $this->container;

        $arguments = [...$arguments, (is_array($next_arguments) ? $next_arguments : $next_arguments)];

        if (isset($class) && !empty($class) && class_exists($class)) {
            $callback = new $class;
        }

        if (!isset($method) && empty($method)) {
            return ReflectionHandler::callMethodArray($callback, ($arguments));
        }

        if (is_object($callback)) {
            return ReflectionHandler::invoke($callback, ($arguments), $container, $method);
        }

        return false;
    }
}
