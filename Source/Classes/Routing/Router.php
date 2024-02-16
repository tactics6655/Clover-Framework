<?php

declare(strict_types=1);

namespace Neko\Classes\Routing;

use Neko\Classes\DependencyInjection\Container;
use Neko\Classes\Routing\Route as RouteObject;
use Neko\Classes\Routing\RouteAnnotationReader;
use Neko\Classes\HTTP\Request as HTTPRequest;
use Neko\Classes\File\Functions as FileFunctions;
use Neko\Classes\Reflection\Handler as ReflectionHandler;
use Neko\Classes\Directory\Handler as DirectoryHandler;

use Neko\Annotation\Route as RouteAnnotation;

use Neko\Implement\EventDispatcherInterface;

use Neko\Enumeration\HTTPRequestMethod as HTTPRequestMethod;

class Router
{
	/** @var Container $container */
	private Container $container;

	/** @var RouteObject[] $routes */
	private array $routes = [];

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
	private function set(string $method, string $pattern, mixed $callback)
	{
		$pattern = $this->addPrefix($pattern);

		$this->addRoute((string)$method, $pattern, $callback);
	}

	public function group($pattern, $callback)
	{
		$this->prependPrefix = $pattern;

		if (ReflectionHandler::isCallable($callback)) {
			ReflectionHandler::callMethodOfClass(self::class, $callback);
		}

		$this->prependPrefix = '';

		return $this;
	}

	public function on(string $method, $pattern, $callback)
	{
		$this->set($method, $pattern, $callback);

		return $this;
	}

	public function get($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::GET, $pattern, $callback);

		return $this;
	}

	public function post($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::POST, $pattern, $callback);

		return $this;
	}

	public function fromDirectory($path)
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

	public function fromFile(string $path)
	{
		$classNames = FileFunctions::getClassName($path);

		/** @var ?RouteAnnotation[] $annotationList */
		$annotationList = [];

		/** @var string[] $classNames */
		foreach ($classNames as $className) {
			$annotationList = array_merge($annotationList, $this->annotationReader->read($className));
		}

		/** @var RouteAnnotation $annotation */
		foreach ($annotationList as $annotation) {
			$method = $annotation->method ?? "";
			$pattern = $annotation->pattern ?? "";
			$middleware = $annotation->middleware ?? "";
			$holder = $annotation->holder ?? [];
			$callback = join('::', $holder);

			$this->addRoute($method, $pattern, $callback, $middleware);
		}

		return $this;
	}

	private function addRoute($method, $pattern, $callback, $middleware = [])
	{
		if (!isset($this->routes[$method])) {
			$this->routes[$method] = [];
		}

		$this->routes[$method][] = new RouteObject($pattern, $callback, $middleware);
	}

	public function delete($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::DELETE, $pattern, $callback);

		return $this;
	}

	public function put($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::PUT, $pattern, $callback);

		return $this;
	}

	public function options($pattern, $callback)
	{
		$this->set(HTTPRequestMethod::OPTIONS, $pattern, $callback);

		return $this;
	}

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

		if (!$this->has($this->method)) {
			return false;
		}

		$routes = $this->getRoute($this->method);
		$urlPathSegments = HTTPRequest::getUrlPathSegments();

		/** @var Route $route */
		foreach ($routes as $route) {
			$match = $route->match($urlPathSegments);

			if (!$match) {
				continue;
			}

			if (isset($this->middlewares)) {
				$route->setMiddlewares($this->middlewares);
			}

			if (isset($this->container)) {
				$route->setContainer($this->container);
			}

			return $route->handle();
		}

		return false;
	}
}
