<?php

declare(strict_types=1);

namespace Clover\Classes\Routing;

use Clover\Classes\Data\ArrayObject as ArrayObject;
use Clover\Classes\Reflection\Handler as ReflectionHandler;
use Clover\Implement\MiddlewareInterface;
use Clover\Classes\Data\StringObject;
use Clover\Classes\Routing\RouteExecutor;
use Clover\Classes\DependencyInjection\Container;
use Clover\Classes\Routing\StackableRequestHandler;
use Clover\Enumeration\Regex;
use Clover\Classes\Regex as Regexr;

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

    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set a middleware for using on route
     * 
     * @param MiddlewareInterface[] $middleware
     * 
     * @return void
     */
    public function setMiddlewares($middleware): void
    {
        $this->middlewares = $middleware ?? [];
    }

    public function hasMiddleware(): bool
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
    public function setNotFoundHandler(?Closure $notFoundHandler): void
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
    public function setContentType($contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * Get a host
     * 
     * @return string
     */
    public function getContentType(): string
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
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * Get a host
     * 
     * @return string
     */
    public function getHost(): string
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
    public function setCallback(Closure|string|array $callback): void
    {
        $this->callback = $callback;
    }

    /**
     * Gets middlewares
     * 
     * @return Closure[]
     */
    public function getMiddlewares(): array
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
    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    public function getContainer(): Container
    {
        return $this->container;
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
    private function isPatternEmpty(): bool
    {
        return $this->pattern->isEmpty();
    }

    /**
     * Get a pattern
     * 
     * @return StringObject|null
     */
    public function getPattern(): StringObject|null
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

        return new RouteExecutor($class, $method, $callback, $this->getArguments(), $this->getContainer());
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
        if ($this->hasMiddleware()) {
            $stackRequestHandler->addMiddlewares($this->getMiddlewares(), $this->getContainer());
        }

        return $stackRequestHandler->handle();
    }

    public function isValidArgument($type, $value): bool
    {
        switch (strtoupper($type)) {
            case Regex::NUMBER->name:
                return Regexr::match('/^[0-9]{1,}$/i', $value)->hasResult();
            case Regex::ALPHABET->name:
                return Regexr::match('/^[A-Za-z]{1,}$/', $value)->hasResult();
            case Regex::ALPHABET_NUMBER->name:
                return Regexr::match('/^[A-Za-z0-9]{1,}$/', $value)->hasResult();
            case Regex::PHONE_NUMBER->name:
                return Regexr::match('/^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$/', $value)->hasResult();
            case Regex::JAPANESE->name:
                return Regexr::match('/^([ぁ-んァ-ヶー一-龠])$/', $value)->hasResult();
            case Regex::KANJI->name:
                return Regexr::match('/^[一-龠]$/', $value)->hasResult();
            case Regex::HIRAGANA->name:
                return Regexr::match('/^([ぁ-ん]+)$/', $value)->hasResult();
            case Regex::KATAKANA->name:
                return Regexr::match('/^([ァ-ヶー]+)$/', $value)->hasResult();
            case Regex::EMAIL->name:
                return Regexr::match("/^[^\"'@]+@[._a-zA-Z0-9-]+\.[a-zA-Z]+$/", $value)->hasResult();
            case Regex::KOREAN->name:
                return Regexr::match("/^[\uAC00-\uD7A3]$/", $value)->hasResult();
            case Regex::BASE64->name:
                return Regexr::match("/^data:[^,]+,/", $value)->hasResult();
            case Regex::KOREAN_ENGLISH->name:
                return Regexr::match("/[\uAC00-\uD7A3a-zA-Z]/", $value)->hasResult();
            default:
                $regexr = sprintf("/%s/", $type);
                return Regexr::isValid($regexr) && Regexr::match($regexr, $value)->hasResult();
        }
    }

    /**
     * Match a pattern by url segments
     * 
     * @param ArrayObject $urlSegments
     * @param string $currentHost
     * @param string $currentContentType
     * 
     * @return bool
     */
    public function match(ArrayObject $urlSegments, string $currentHost, string $currentContentType): bool
    {
        if ($this->isPatternEmpty()) {
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

        $count = $separatedSegments->size();

        if ($count <= 0) {
            return false;
        }

        if ($urlSegments->sizeGreaterThan($count)) {
            return false;
        }

        for ($i = 0; $i < $count; $i++) {
            $segment = $urlSegments[$i] ?? null;
            $routeSegment = $separatedSegments->get($i);

            if ($routeSegment->match('/^({\w*})\??((:\(?[^\/]+\)?)?)$/', $match)) {
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
