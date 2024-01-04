<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP;

use Xanax\Classes\Event\Instance as EventInterface;
use Xanax\Classes\HTTP\Router\Route as RouteObject;
use Xanax\Classes\HTTP\Router\RouteAnnotationReader;
use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Enumeration\HTTPRequestMethod as HTTPRequestMethod;
use Xanax\Classes\File\Functions as FileFunctions;
use Xanax\Classes\Reflection\Handler as ReflectionHandler;
use Xanax\Classes\Directory\Handler as DirectoryHandler;
use Xanax\Implement\EventDispatcherInterface;

class Router
{
	private $middlewares = array();

	private $container = array();

	private $routes = array();

	private $method;

	private $globalPrefix;

	private $annotationReader;

	public function __construct(private readonly ?EventDispatcherInterface $eventDispatcher = null)
	{
		$this->annotationReader = new RouteAnnotationReader();
	}

	public function setContainer($container)
	{
		$this->container = $container;
	}

	private function addPrefix($pattern)
	{
		return $this->globalPrefix . $pattern;
	}

	private function set(string $method, $pattern, $callback)
	{
		$pattern = $this->addPrefix($pattern);

		$this->addRoute((string)$method, $pattern, $callback);
	}

	public function group($pattern, $callback)
	{
		$this->globalPrefix = $pattern;

		if (ReflectionHandler::isCallable($callback)) {
			ReflectionHandler::callClassMethod(self::class, $callback);
		}

		$this->globalPrefix = '';

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
			$holder = $annotation->holder ?? [];
			$callback = join('::', $holder);

			$this->addRoute($method, $pattern, $callback);
		}

		return $this;
	}

	private function addRoute($method, $pattern, $callback)
	{
		if (!isset($this->routes[$method])) {
			$this->routes[$method] = [];
		}

		$this->routes[$method][] = new RouteObject($pattern, $callback);
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

			return $route->handle($this->container);
		}

		return false;
	}
}
