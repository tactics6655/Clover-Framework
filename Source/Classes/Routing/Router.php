<?php

declare(strict_types=1);

namespace Clover\Classes\Routing;

use Clover\Classes\DependencyInjection\Container;
use Clover\Classes\Routing\Route as RouteObject;
use Clover\Classes\Routing\RouteAnnotationReader;
use Clover\Classes\HTTP\Request as HTTPRequest;
use Clover\Classes\File\Functions as FileFunctions;
use Clover\Classes\Reflection\Handler as ReflectionHandler;
use Clover\Classes\Directory\Handler as DirectoryHandler;

use Clover\Annotation\Route as RouteAnnotation;

use Clover\Implement\EventDispatcherInterface;

use Clover\Enumeration\HTTPRequestMethod as HTTPRequestMethod;

use Closure;

class Router
{
	/** @var Container $container */
	private Container $container;

	/** @var RouteObject[] $routes */
	private array $routes = [];

	private string|null|Closure $notFoundHandler = null;

	/** @var Middleware[] $middlewares */
	private array $middlewares;

	private string|HTTPRequestMethod $method;

	private string $prependPrefix = "";

	private RouteAnnotationReader $annotationReader;

	public function __construct(private readonly ?EventDispatcherInterface $eventDispatcher = null)
	{
		$this->annotationReader = new RouteAnnotationReader();
	}

	/**
	 * Gets middlewares
	 * 
	 * @return Middleware[]
	 */
	public function getMiddlewares()
	{
		return $this->middlewares;
	}

	/**
	 * Set a middleware for using globally
	 * 
	 * @param Middleware[] $middleware
	 * 
	 * @return Router
	 */
	public function setMiddlewares(...$middleware)
	{
		$this->middlewares = $middleware;

		return $this;
	}

	public function setNotFoundHandler(string|null|Closure $handler)
	{
		$this->notFoundHandler = $handler;
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
	 * Set a pre-append prefix on pattern
	 */
	private function addPrefix($pattern)
	{
		return $this->prependPrefix . $pattern;
	}

	/**
	 * Set a route
	 * 
	 * @param string $method
	 * @param string $pattern
	 * @param mixed $callback
	 */
	private function set(string $method, string $pattern, mixed $callback, string $host = "*", string $contentType = "*")
	{
		$pattern = $this->addPrefix($pattern);

		$this->addRoute((string)$method, $pattern, $callback, [], $host, $contentType);
	}

	/**
	 * Add group route
	 */
	public function group($pattern, $callback)
	{
		$this->prependPrefix = $pattern;

		if (ReflectionHandler::isCallable($callback)) {
			ReflectionHandler::callMethodOfClass(self::class, $callback);
		}

		$this->prependPrefix = '';

		return $this;
	}

	/**
	 * Add route for Any-Method
	 */
	public function on(string $method, $pattern, $callback)
	{
		$this->set($method, $pattern, $callback);

		return $this;
	}

	/**
	 * Add route for GET-Method
	 */
	public function get($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::GET, $pattern, $callback);

		return $this;
	}

	/**
	 * Add route for POST-Method
	 */
	public function post($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::POST, $pattern, $callback);

		return $this;
	}

	/**
	 * Parse annotation from file of class base route in directories
	 * 
	 * @param string $path
	 */
	public function fromDirectory(string $path)
	{
		$fileList = DirectoryHandler::getList($path, 'file', true, true);

		if (!$fileList) {
			return false;
		}

		foreach ($fileList as $file) {
			$this->fromFile($file);
		}

		return $this;
	}

	/**
	 * Parse annotation from file of class base route
	 * 
	 * @param string $path
	 */
	public function fromFile(string $path)
	{
		$classNames = FileFunctions::getClassNames($path);

		/** @var ?RouteAnnotation[] $annotationList */
		$annotationList = [];

		/** @var string[] $classNames */
		foreach ($classNames as $className) {
			$annotationList = array_merge($annotationList, $this->annotationReader->read($className));
		}

		/** @var RouteAnnotation $annotation */
		foreach ($annotationList as $annotation) {
			$host = $annotation->host ?? "*";
			$method = $annotation->method ?? "";
			$pattern = $annotation->pattern ?? "";
			$middleware = $annotation->middleware ?? "";
			$contentType = $annotation->contentType ?? "*";
			$notFoundHandler = $annotation->notFoundHandler;
			$holder = $annotation->holder ?? [];
			$callback = join('::', $holder);

			if ($notFoundHandler != null) {
				$this->setNotFoundHandler($notFoundHandler);
			}

			$this->addRoute(method: $method, pattern: $pattern, callback: $callback, middleware: $middleware, host: $host, contentType: $contentType);
		}

		return $this;
	}

	/**
	 * Add route when route match
	 */
	private function addRoute($method, $pattern, $callback, $middleware = [], $host = "*", $contentType = "*")
	{
		if (!isset($this->routes[$method])) {
			$this->routes[$method] = [];
		}

		$routeObject = new RouteObject($pattern, $callback, $middleware);
		$routeObject->setHost($host);
		$routeObject->setContentType($contentType);

		$this->routes[$method][] = $routeObject;
	}

	/**
	 * Add route for DELETE-Method
	 */
	public function delete($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::DELETE, $pattern, $callback);

		return $this;
	}

	/**
	 * Add route for PUT-Method
	 */
	public function put($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::PUT, $pattern, $callback);

		return $this;
	}

	/**
	 * Add route for OPTIONS-Method
	 */
	public function options($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::OPTIONS, $pattern, $callback);

		return $this;
	}

	/**
	 * Add route for PATCH-Method
	 */
	public function patch($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::PATCH, $pattern, $callback);

		return $this;
	}

	/**
	 * Check that if exists specify method routes
	 * 
	 * @param string $method
	 * 
	 * @return bool
	 */
	private function has(string $method)
	{
		return isset($this->routes[$method]);
	}

	/**
	 * Get a route
	 * 
	 * @param string $method
	 * 
	 * @return Route
	 */
	private function getRoute(string $method)
	{
		return $this->routes[$method];
	}

	/**
	 * Handle a matched callback
	 * 
	 * @return mixed
	 */
	public function handle()
	{
		$this->method = HTTPRequest::getMethod();

		/** When not contain method from added routes */
		if (!$this->has($this->method)) {
			return false;
		}

		$routes = $this->getRoute($this->method);
		$urlPathSegments = HTTPRequest::getUrlPathSegments();
		$host = HTTPRequest::getHttpHost();
		$contentType = HTTPRequest::getContentType();

		/** @var Route $route */
		foreach ($routes as $route) {
			$match = $route->match($urlPathSegments, $host, $contentType);

			if (!$match) {
				continue;
			}

			/** Pass middlewares when parent middelware are exists */
			if (isset($this->middlewares)) {
				$route->setMiddlewares($this->middlewares);
			}

			/** Pass container when parent container is exists */
			if (isset($this->container)) {
				$route->setContainer($this->container);
			}

			/** Handle an routes */
			return $route->handle();
		}

		/** Fire not found handler */
		if ($this->notFoundHandler != null) {
			$callback = $this->notFoundHandler;

			[$class, $method] = ReflectionHandler::getCallMethodFromString($callback);

			$executor = new RouteExecutor($class, $method, $callback, [], $this->container);
			return $executor->__invoke([]);
		}

		/** When not found matched route */
		return false;
	}
}
