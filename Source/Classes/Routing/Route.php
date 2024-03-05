<?php

declare(strict_types=1);

namespace Clover\Classes\Routing;

use Clover\Classes\Reflection\Handler as ReflectionHandler;
use Clover\Implement\MiddlewareInterface;
use Clover\Classes\Data\StringObject;
use Clover\Classes\Routing\RouteExecutor;
use Clover\Classes\DependencyInjection\Container;
use Clover\Classes\Routing\StackableRequestHandler;
use Clover\Enumeration\Regex;

use Closure;

class Route
{

    private Closure | string | array $callback;

    private Container $container;

    /** @var Closure[] */
    private array $middlewares = [];

    private ?Closure $notFoundHandler = null;

    private ?StringObject $pattern;

    private string $contentType = "*";

    private string $host = "*";

    /** @var string[] */
    protected array $arguments = [];

    /**
     * Constructor of route
     * 
     * @param string $pattern
     * @param mixed $callback
     * @param MiddlewareInterface[] $middleware
     * @param string $host
     * @param string $contentType
     */
    public function __construct(string $pattern = "*", mixed $callback = null, $middleware = [])
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
        $this->middlewares = $middleware ?? [];
    }

    public function hasMiddleware()
    {
        return count($this->middlewares) > 0;
    }

    /**
     * Set a host
     * 
     * @param string $host
     * 
     * @return void
     */
    public function setNotFoundHandler(?Closure $notFoundHandler)
    {
        $this->notFoundHandler = $notFoundHandler;
    }

    /**
     * Get a host
     * 
     * @return Closure
     */
    private function getNotFoundHandler(): ?Closure
    {
        return $this->notFoundHandler;
    }

    /**
     * Set a host
     * 
     * @param string $host
     * 
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Get a host
     * 
     * @return string
     */
    private function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Set a host
     * 
     * @param string $host
     * 
     * @return void
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Get a host
     * 
     * @return string
     */
    private function getHost(): string
    {
        return $this->host;
    }

    /**
     * Set a callback
     * 
     * @param Closure|string $callback
     * 
     * @return void
     */
    public function setCallback(Closure|string|array $callback)
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
    private function getCallback(): Closure|string|array
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
        $callback = $this->getCallback();

        [$class, $method] = ReflectionHandler::getCallMethodFromString($callback);

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

    public function isValidArgument($type, $value): bool
    {
        switch (strtoupper($type)) {
            case Regex::NUMBER->name:
                return preg_match('/^[0-9]{1,}$/i', $value, $match) === 1;
            case Regex::ALPHABET->name:
                return preg_match('/^[A-Za-z]{1,}$/', $value, $match) === 1;
            case Regex::ALPHABET_NUMBER->name:
                return preg_match('/^[A-Za-z0-9]{1,}$/', $value, $match) === 1;
            case Regex::PHONE_NUMBER->name:
                return preg_match('/^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$/', $value, $match) === 1;
            case Regex::JAPANESE->name:
                return preg_match('/^([ぁ-んァ-ヶー一-龠])$/', $value, $match) === 1;
            case Regex::KANJI->name:
                return preg_match('/^[一-龠]$/', $value, $match) === 1;
            case Regex::HIRAGANA->name:
                return preg_match('/^([ぁ-ん]+)$/', $value, $match) === 1;
            case Regex::KATAKANA->name:
                return preg_match('/^([ァ-ヶー]+)$/', $value, $match) === 1;
            case Regex::EMAIL->name:
                return preg_match("/^[^\"'@]+@[._a-zA-Z0-9-]+\.[a-zA-Z]+$/", $value, $match) === 1;
            case Regex::KOREAN->name:
                return preg_match("/^[\uAC00-\uD7A3]$/", $value, $match) === 1;
            case Regex::BASE64->name:
                return preg_match("/^data:[^,]+,/", $value, $match) === 1;
            case Regex::KOREAN_ENGLISH->name:
                return preg_match("/[\uAC00-\uD7A3a-zA-Z]/", $value, $match) === 1;
            default:
                $isValid = @preg_match($type, '') === false;

                return $isValid && preg_match($type, $value, $match) === 1;
        }
    }

    /**
     * Match a pattern by url segments
     * 
     * @param array $urlSegments
     * @param string $host
     * 
     * @return bool
     */
    public function match(array $urlSegments, string $currentHost, string $currentContentType): bool
    {
        if ($this->isEmptyPattern()) {
            return false;
        }

        $contentType = $this->getContentType();
        if ($contentType != "*" && !str_starts_with($currentContentType, $contentType)) {
            return false;
        }

        $host = $this->getHost();
        if ($host != "*" && $host != $currentHost) {
            return false;
        }

        $separatedSegments = $this->getPattern()->trim('/')->split('/');

        $count = $separatedSegments->size()->toInteger();

        if ($count <= 0) {
            return false;
        }

        if ($count < count($urlSegments)) {
            return false;
        }

        for ($i = 0; $i < $count; $i++) {
            $segment = $urlSegments[$i] ?? null;
            $routeSegment = $separatedSegments->get($i);

            if (preg_match('/^({\w*})\??((:\(?[^\/]+\)?)?)$/', $routeSegment->__toString(), $match)) {
                [$raw, $parameter, $type] = $match;

                if ($segment == null) {
                    return false;
                }

                if (!empty($type)) {
                    $type = substr($type, 2, strlen($type) - 3);

                    if (!($this->isValidArgument($type, $segment))) {
                        return false;
                    }
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
