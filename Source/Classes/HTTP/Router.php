<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP;

use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Enumeration\HTTPRequestMethod as HTTPRequestMethod;
use Xanax\Classes\File\Functions as FileFunctions;
use Xanax\Annotation\Route;
use Xanax\Annotation\Prefix;
use Xanax\Annotation\Middleware;
use Xanax\Classes\Reflection\Handler as ReflectionHandler;
use Xanax\Classes\Directory\Handler as DirectoryHandler;

use ReflectionClass;
use ReflectionMethod;
use Reflector;

class Router
{
	private $middlewares = array();

	private static $container = array();

	private static $variable_regex = '/^({\w*})$/';

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
		return self::$global_prefix.$pattern;
	}

	private static function Set(string $method, $pattern, $callback)
	{
		$pattern = self::addPrefix($pattern);

		self::addRoute((string)$method, $pattern, $callback);
	}

	public static function Group($pattern, $callback)
	{
		self::$global_prefix = $pattern;

		if (is_callable($callback))
		{
			call_user_func($callback);
		}

		self::$global_prefix = '';

		return self::class;
	}

	public static function On(string $method, $pattern, $callback)
	{
		self::Set($method, $pattern, $callback);
	}

	public static function Get($pattern, $callback)
	{
		self::Set(HTTPRequestMethod::GET, $pattern, $callback);
	}

	public static function Post($pattern, $callback)
	{
		self::Set(HTTPRequestMethod::POST, $pattern, $callback);
	}

	public static function fromDirectory($path)
	{
		$fileList = DirectoryHandler::getList($path, 'file', true, true);

		foreach ($fileList as $file)
		{
			self::fromFile($file);
		}
	}

	private static function getAnnotationFromClassName($class_name)
	{
		$annotations = [];

		$class = new ReflectionClass($class_name);

		foreach ($class->getMethods() as $method) 
		{
			if ($method->isStatic() || $method->isPrivate() || $method->isProtected()) 
			{
				continue;
			}

			$route_annotation = ReflectionHandler::getAnnotations($method, Route::class);
			if (!isset($route_annotation[0]))
			{
				continue;
			}

			$descriptor = $route_annotation[0];
			$prefix_annotation = ReflectionHandler::getAnnotations($class, Prefix::class);
			if (isset($prefix_annotation[0])) 
			{
				$descriptor->pattern = $prefix_annotation[0]->value.$descriptor->pattern;
			}
			$descriptor->holder = [$class->getName(), $method->getName()];

			$middleware_annotation = ReflectionHandler::getAnnotations($class, Middleware::class);
			if (isset($middleware_annotation[0])) 
			{
				$descriptor->middleware = $middleware_annotation[0]->value;
			}

			$annotations = $descriptor;
		}

		return $annotations;
	}

	public static function fromFile($path)
	{
		$class_names = FileFunctions::getClassName($path);

		$annotations = [];

		foreach ($class_names as $class_name)
		{
			$annotations[] = self::getAnnotationFromClassName($class_name);
		}

		foreach ($annotations as $annotation)
		{
			$method = $annotation->method ?? "";
			$pattern = $annotation->pattern ?? "";
			$holder = $annotation->holder ?? [];
			$callback = join('::', $holder);

			self::addRoute($method, $pattern, $callback);
		}
	}

	private static function addRoute($method, $pattern, $callback)
	{
		if (!isset(self::$routes[$method]))
		{
			self::$routes[$method] = [];
		}

		self::$routes[$method][] = array(
			'pattern' => $pattern,
			'callback' => $callback
		);
	}

	public static function Delete($pattern, $callback)
	{
		self::Set(HTTPRequestMethod::DELETE, $pattern, $callback);
	}

	public static function Put($pattern, $callback)
	{
		self::Set(HTTPRequestMethod::PUT, $pattern, $callback);
	}

	public static function Options($pattern, $callback)
	{
		self::Set(HTTPRequestMethod::OPTIONS, $pattern, $callback);
	}

	public static function Patch($pattern, $callback)
	{
		self::Set(HTTPRequestMethod::PATCH, $pattern, $callback);
	}

	public static function setSegments()
	{
		$routes = self::getRoute(self::$method);

		foreach ($routes as $key => $route)
		{
			if (!isset($route['pattern']))
			{
				continue;
			}

			$segments = explode('/', trim($route['pattern'] ?? "", '/'));

			if (count($segments) <= 0)
			{
				continue;
			}

			self::$routes[self::$method][$key]['segment'] = [];
			self::$routes[self::$method][$key]['parameter'] = [];

			foreach ($segments as $segment)
			{
				self::$routes[self::$method][$key]['segment'][] = $segment;

				if (preg_match(self::$variable_regex, $segment))
				{
					self::$routes[self::$method][$key]['parameter'][] = $segment;
				}
			}
		}
	}

	protected static function isValidRoute($route, $segments)
	{
		for ($z = 0; $z < count($segments); $z++)
		{
			$segment = $segments[$z];
			$route_segment = $route['segment'][$z];

			if (preg_match(self::$variable_regex, $route_segment, $match))
			{
				self::$arguments[] = $segment;

				continue;
			}

			if ($route_segment != $segment)
			{
				return false;
			}
		}

		return true;
	}

	private static function Has($method)
	{
		return isset(self::$routes[$method]);
	}

	private static function getRoute($method)
	{
		return self::$routes[$method];
	}

	private static function callMethod($callback)
	{
		if (!is_callable($callback) && is_string($callback))
		{
			$static_method_arguments = explode('::', $callback);

			$class_name = $static_method_arguments[0];
			$method_name = $static_method_arguments[1];
		}

		if (class_exists($class_name))
		{
			$callback = new $class_name;
		}

		if (!isset($method_name))
		{
			return ReflectionHandler::Invoke($callback, $method_name, (self::$arguments ?? array()), []);
		}

		if (is_object($callback))
		{
			return ReflectionHandler::Invoke($callback, $method_name, (self::$arguments ?? array()), self::$container);
		}
	}

	public static function Run($reflection = true)
	{
		self::$method = HTTPRequest::getRequestMethod();

		if (!self::Has(self::$method))
		{
			return false;
		}

		self::setSegments();

		$routes = self::getRoute(self::$method);
		$url_path_segments = HTTPRequest::getUrlPathSegments();

		for ($i = 0; $i < count($routes); $i++)
		{
			$route = $routes[$i];

			if (!is_countable($route['segment']) || !is_countable($url_path_segments))
			{
				return false;
			}

			if (count($route['segment']) != count($url_path_segments))
			{
				continue;
			}
	
			$isValid = self::isValidRoute($route, $url_path_segments);

			if (!$isValid)
			{
				continue;
			}

			$callback = $route['callback'];

			return self::callMethod($callback);
		}
	}

}
