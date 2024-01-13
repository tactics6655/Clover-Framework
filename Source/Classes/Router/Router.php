<?php

declare(strict_types=1);

namespace Xanax\Classes\Router;

use Xanax\Classes\DependencyInjection\Container;
use Xanax\Classes\Router\Route as RouteObject;
use Xanax\Classes\Router\RouteAnnotationReader;
use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Classes\File\Functions as FileFunctions;
use Xanax\Classes\Reflection\Handler as ReflectionHandler;
use Xanax\Classes\Directory\Handler as DirectoryHandler;

use Xanax\Implement\EventDispatcherInterface;

use Xanax\Enumeration\HTTPRequestMethod as HTTPRequestMethod;

class Router
{
	/** @var Container[] $container */
	private Container $container;

	/** @var RouteObject[] $routes */
	private array $routes = [];

	private array $middlewares;

	private string|HTTPRequestMethod $method;

	private string $prependPrefix = "";

	private RouteAnnotationReader $annotationReader;

	public function __construct(private readonly ?EventDispatcherInterface $eventDispatcher = null)
	{
		$this->annotationReader = new RouteAnnotationReader();
	}

	/**
	 * Set a middleware for using globally
	 */
	public function setMiddleware(...$middleware)
	{
		$this->middlewares = $middleware;

		return $this;
	}

	/**
	 * Set a container
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
	 * @param string $callback
	 */
	private function set(string $method, string $pattern, string $callback)
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

		foreach ($fileList as $file) {
			$this->fromFile($file);
		}

		return $this;
	}

	public function fromFile($path)
	{
		$classNames = FileFunctions::getClassName($path);

		$annotationList = [];

		foreach ($classNames as $className) {
			$annotationList = array_merge($annotationList, $this->annotationReader->read($className));
		}

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

	private function has($method)
	{
		return isset($this->routes[$method]);
	}

	private function getRoute($method)
	{
		return $this->routes[$method];
	}

	public function handle()
	{
		$this->method = HTTPRequest::getRequestMethod();

		if (!$this->has($this->method)) {
			return false;
		}

		$routes = $this->getRoute($this->method);
		$urlPathSegments = HTTPRequest::getUrlPathSegments();

		foreach ($routes as $route) {
			$match = $route->match($urlPathSegments);

			if (!$match) {
				continue;
			}

			if (isset($this->middlewares)) {
				$route->setMiddleware($this->middlewares);
			}

			$route->setContainer($this->container);

			return $route->handle();
		}

		return false;
	}
}
