<?php

declare(strict_types=1);

namespace Neko\Classes\Routing;

use Neko\Implement\MiddlewareInterface;
use Neko\Classes\Data\StringObject;
use Neko\Classes\Routing\RouteExecutor;
use Neko\Classes\DependencyInjection\Container;
use Neko\Classes\Reflection\Handler as ReflectionHandler;

use SplStack;
use Closure;
use SplDoublyLinkedList;

class Route
{
    private Closure | string $callback;

    private Container $container;

    /** @var Closure[] */
    private array $middlewares = array();

    private ?StringObject $pattern;

    /** @var string[] */
    protected array $arguments = [];

    /**
     * Construct of route
     * 
     * @param string $pattern
     * @param string $callback
     * @param MiddlewareInterface[] $middleware
     */
    public function __construct(string $pattern, string $callback, $middleware = [])
    {
        $this->pattern = new StringObject($pattern);
        $this->callback = $callback;

        if (!empty($middleware)) {
            $this->middlewares[] = $middleware;
        }
    }

    /**
     * Set a middleware for using on route
     * 
     * @param array(MiddlewareInterface) $middleware
     */
    public function setMiddleware($middleware)
    {
        $this->middlewares = $middleware;
    }

    /**
     * Set a container
     * 
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get a callback
     * 
     * @return Closure|string
     */
    private function getCallback() :Closure|string
    {
        return $this->callback;
    }

    /**
     * Checks if pattern empty
     * 
     * @return bool
     */
    private function isEmptyPattern() :bool
    {
        return $this->pattern->isEmpty();
    }

    /**
     * Get a pattern
     * 
     * @return StringObject|null
     */
    private function getPattern() :StringObject|null
    {
        return $this->pattern;
    }

    /**
     * Get a executor
     * 
     * @return RouteExecutor
     */
    private function getExecutor() :RouteExecutor
    {
        $class = null;
        $method = null;
        $callback = $this->getCallback();

        if (!is_callable($callback) && is_string($callback)) {
            [$class, $method] = explode('::', $this->callback);
        }

        return new RouteExecutor($class, $method, $callback, $this->arguments, $this->container);
    }

    /**
     * Handle a routes
     * 
     * @return mixed
     */
    public function handle() :mixed
    {
        $stack = new SplStack();
        $stack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);
        $stack[] = $this->getExecutor();

        foreach ($this->middlewares as $middleware) {
            $next = $stack->top();

            $stack[] = function () use ($middleware, $next) {
                if (!empty($middleware) && is_string($middleware)) {
                    $caller = ReflectionHandler::getNewInstance($middleware);

                    return ReflectionHandler::invoke($caller, [$next], $this->container, 'handle');
                }

                return ReflectionHandler::callMethod($middleware, $next);
            };
        }

        $top = $stack->top();

        return $top();
    }

    /**
     * Match a pattern by url segments
     * 
     * @param array $urlSegments
     * 
     * @return bool
     */
    public function match(array $urlSegments) :bool
    {
        if ($this->isEmptyPattern()) {
            return false;
        }

        $separatedSegments = $this->getPattern()->trim('/')->split('/');

        $count = $separatedSegments->length()->toInteger();

        if ($count <= 0) {
            return false;
        }

        for ($z = 0; $z < $count; $z++) {
            $segment = $urlSegments[$z] ?? null;
            $routeSegment = $separatedSegments->get($z);

            if (preg_match('/^({\w*})$/', $routeSegment->__toString(), $match)) {
                if ($segment == null) {
                    return false;
                }

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
