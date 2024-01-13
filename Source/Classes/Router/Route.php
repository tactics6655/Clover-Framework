<?php

declare(strict_types=1);

namespace Xanax\Classes\Router;

use Xanax\Classes\Data\StringObject;

use Xanax\Classes\Router\RouteExecutor;
use Xanax\Classes\DependencyInjection\Container;

use Xanax\Classes\Reflection\Handler as ReflectionHandler;

use SplStack;

class Route
{
    private \Closure | string $callback;

    private Container $container;

    private array $middlewares = array();

    private ?StringObject $pattern;

    protected array $arguments = [];

    public function __construct($pattern, string $callback, $middleware = [])
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
     * @param Container $container
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
     */
    private function getCallback()
    {
        return $this->callback;
    }

    /**
     * Checks if pattern empty
     */
    private function isEmptyPattern()
    {
        return $this->pattern->isEmpty();
    }

    /**
     * Get a pattern
     */
    private function getPattern()
    {
        return $this->pattern;
    }

    private function getExecutor()
    {
        $class = null;
        $method = null;
        $callback = $this->getCallback();

        if (!is_callable($callback) && is_string($callback)) {
            [$class, $method] = explode('::', $this->callback);
        }

        $handler = new RouteExecutor($class, $method, $callback, $this->arguments, $this->container);

        return $handler;
    }

    /**
     * Handle a routes
     * 
     * @return mixed
     */
    public function handle()
    {
        $stack = new SplStack();
        $stack->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);
        $stack[] = $this->getExecutor();

        foreach ($this->middlewares as $middleware) {
            $next = $stack->top();

            $stack[] = function () use ($middleware, $next) {
                if (!empty($middleware) && is_string($middleware)) {
                    $caller = new $middleware();

                    return ReflectionHandler::invoke($caller, [$next], $this->container, 'handle'); //$caller->handle($next);
                }

                var_dump($next);
                return call_user_func($middleware, $next);
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
    public function match(array $urlSegments)
    {
        if ($this->isEmptyPattern()) {
            return false;
        }

        $separatedSegments = $this->getPattern()->trim('/')->split('/');

        $count = $separatedSegments->length();

        if ($count <= 0) {
            return false;
        }

        for ($z = 0; $z < $count; $z++) {
            $segment = $urlSegments[$z];
            $routeSegment = $separatedSegments->get($z);

            if (preg_match('/^({\w*})$/', $routeSegment->__toString(), $match)) {
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
