<?php

declare(strict_types=1);

namespace Neko\Classes\Routing;

use Neko\Implement\MiddlewareInterface;
use Neko\Classes\Data\StringObject;
use Neko\Classes\Routing\RouteExecutor;
use Neko\Classes\DependencyInjection\Container;
use Neko\Classes\Reflection\Handler as ReflectionHandler;
use Neko\Classes\Routing\QueueableRequestHandler;
use Neko\Classes\Routing\StackableRequestHandler;

use Closure;
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
        $this->setPattern($pattern);
        $this->setCallback($callback);

        if (!empty($middleware)) {
            $this->setMiddlewares($middleware);
        }
    }

    /**
     * Set a middleware for using on route
     * 
     * @param MiddlewareInterface[] $middleware
     * 
     * @return void
     */
    public function setMiddlewares($middleware)
    {
        $this->middlewares = $middleware;
    }

    /**
     * Set a callback
     * 
     * @param Closure|string $callback
     * 
     * @return void
     */
    public function setCallback(Closure|string $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Gets middlewares
     * 
     * @return Closure[]
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Set a container
     * 
     * @param Container $container
     * 
     * @return void
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
    private function getCallback(): Closure|string
    {
        return $this->callback;
    }

    /**
     * Set a route pattern
     * 
     * @param string $pattern
     * 
     * @return void
     */
    public function setPattern(string $pattern): void
    {
        $this->pattern = new StringObject($pattern);
    }

    /**
     * Checks if pattern empty
     * 
     * @return bool
     */
    private function isEmptyPattern(): bool
    {
        return $this->pattern->isEmpty();
    }

    /**
     * Get a pattern
     * 
     * @return StringObject|null
     */
    private function getPattern(): StringObject|null
    {
        return $this->pattern;
    }

    /**
     * Get a executor
     * 
     * @return RouteExecutor
     */
    private function getExecutor(): RouteExecutor
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
    public function handle(): mixed
    {
        $stackRequestHandler = new StackableRequestHandler();
        $stackRequestHandler->pushItem($this->getExecutor());
        $stackRequestHandler->addMiddlewares($this->middlewares, $this->container);
        return $stackRequestHandler->handle();
    }

    /**
     * Match a pattern by url segments
     * 
     * @param array $urlSegments
     * 
     * @return bool
     */
    public function match(array $urlSegments): bool
    {
        if ($this->isEmptyPattern()) {
            return false;
        }

        $separatedSegments = $this->getPattern()->trim('/')->split('/');

        $count = $separatedSegments->length()->toInteger();

        if ($count <= 0) {
            return false;
        }

        if ($count < count($urlSegments)) {
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
