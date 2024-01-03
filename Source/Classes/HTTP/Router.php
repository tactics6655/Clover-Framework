<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP;

use Xanax\Classes\HTTP\Router\Route as RouteObject;
use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Enumeration\HTTPRequestMethod as HTTPRequestMethod;
use Xanax\Classes\File\Functions as FileFunctions;
use Xanax\Annotation\Route;
use Xanax\Annotation\Prefix;
use Xanax\Annotation\Middleware;
use Xanax\Classes\Reflection\Handler as ReflectionHandler;
use Xanax\Classes\Directory\Handler as DirectoryHandler;

use ReflectionClass;

class Router
{
	private $middlewares = array();

	private static $container = array();

	private static $routes = array();

	private static $arguments;

	private static $url_path;

	private static $method;

	private static $segments;

	private static $global_prefix;

	public static function setContainer($container)
	{
		self::$container = $container;
	}

	private static function addPrefix($pattern)
	{
		return self::$global_prefix . $pattern;
	}

	private static function set(string $method, $pattern, $callback)
	{
		$pattern = self::addPrefix($pattern);

		self::addRoute((string)$method, $pattern, $callback);
	}

	public static function group($pattern, $callback)
	{
		self::$global_prefix = $pattern;

		if (ReflectionHandler::isCallable($callback)) {
			ReflectionHandler::callMethod(self::class, $callback);
		}

		self::$global_prefix = '';

		return self::class;
	}

	public static function on(string $method, $pattern, $callback)
	{
		self::set($method, $pattern, $callback);
	}

	public static function get($pattern, $callback)
	{
		self::set(HTTPRequestMethod::GET, $pattern, $callback);
	}

	public static function post($pattern, $callback)
	{
		self::set(HTTPRequestMethod::POST, $pattern, $callback);
	}

	public static function fromDirectory($path)
	{
		$fileList = DirectoryHandler::getList($path, 'file', true, true);

		foreach ($fileList as $file) {
			self::fromFile($file);
		}
	}

	private static function getAnnotationFromClassName($class_name)
	{
		$annotations = [];

		$class = new ReflectionClass($class_name);

		foreach ($class->getMethods() as $method) {
			if ($method->isStatic() || $method->isPrivate() || $method->isProtected()) {
				continue;
			}

			$route_annotation = ReflectionHandler::getAnnotations($method, Route::class);
			if (!isset($route_annotation[0])) {
				continue;
			}

			$descriptor = $route_annotation[0];
			$prefix_annotation = ReflectionHandler::getAnnotations($class, Prefix::class);
			if (isset($prefix_annotation[0])) {
				$descriptor->pattern = $prefix_annotation[0]->value . $descriptor->pattern;
			}
			$descriptor->holder = [$class->getName(), $method->getName()];

			$middleware_annotation = ReflectionHandler::getAnnotations($class, Middleware::class);
			if (isset($middleware_annotation[0])) {
				$descriptor->middleware = $middleware_annotation[0]->value;
			}

			$annotations[] = $descriptor;
		}

		return $annotations;
	}

	public static function fromFile($path)
	{
		$classNames = FileFunctions::getClassName($path);

		$annotationList = [];

		foreach ($classNames as $className) {
			$annotationList = array_merge($annotationList, self::getAnnotationFromClassName($className));
		}

		foreach ($annotationList as $annotation) {
			$method = $annotation->method ?? "";
			$pattern = $annotation->pattern ?? "";
			$holder = $annotation->holder ?? [];
			$callback = join('::', $holder);

			self::addRoute($method, $pattern, $callback);
		}
	}

	private static function addRoute($method, $pattern, $callback)
	{
		if (!isset(self::$routes[$method])) {
			self::$routes[$method] = [];
		}

		self::$routes[$method][] = new RouteObject($pattern, $callback);
	}

	public static function delete($pattern, $callback)
	{
		self::set(HTTPRequestMethod::DELETE, $pattern, $callback);
	}

	public static function put($pattern, $callback)
	{
		self::set(HTTPRequestMethod::PUT, $pattern, $callback);
	}

	public static function options($pattern, $callback)
	{
		self::set(HTTPRequestMethod::OPTIONS, $pattern, $callback);
	}

	public static function patch($pattern, $callback)
	{
		self::set(HTTPRequestMethod::PATCH, $pattern, $callback);
	}

	private static function has($method)
	{
		return isset(self::$routes[$method]);
	}

	private static function getRoute($method)
	{
		return self::$routes[$method];
	}

	public static function run()
	{
		self::$method = HTTPRequest::getRequestMethod();

		if (!self::has(self::$method)) {
			return false;
		}

		$routes = self::getRoute(self::$method);
		$urlPathSegments = HTTPRequest::getUrlPathSegments();

		foreach ($routes as $route) {
			$match = $route->match($urlPathSegments);

			if (!$match) {
				continue;
			}

			return $route->handle(self::$container);
		}

		return false;
	}
}
