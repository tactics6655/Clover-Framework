<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP;

use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Enumeration\HTTPRequestMethod as HTTPRequestMethod;
use Xanax\Classes\File\Functions as FileFunctions;
use Xanax\Annotation\Route;
use Xanax\Annotation\Prefix;
use Xanax\Classes\Reflection\Handler as ReflectionHandler;

use ReflectionClass;
use ReflectionMethod;
use Reflector;

class Router
{

	private $middlewares = array();

	private static $variable_regex = '/^({\w*})$/';

	private static $routes = array();

	private static $arguments;

	private static $url_path;

	private static $method;

	private static $segments;

	private static $global_prefix;

	private static function addPrefix($pattern)
	{
		return self::$global_prefix.$pattern;
	}

	private static function Set(string $method, $pattern, $callback)
	{
		$pattern = self::addPrefix($pattern);

		self::$routes[(string)$method][] = array(
			'pattern' => $pattern,
			'callback' => $callback,
		);
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

	public static function fromFile($path)
	{
		$class_names = FileFunctions::getClassName($path);

		$results = [];

		foreach ($class_names as $class_name)
		{
			$class = new ReflectionClass($class_name);

			foreach ($class->getMethods() as $method) {
				if ($method->isStatic() ||
					$method->isPrivate() ||
					$method->isProtected()) {
					continue;
				}

				$annotations = ReflectionHandler::getAnnotations($method, Route::class);
				if (isset($annotations[0]))
				{
					$descriptor = $annotations[0];
					
					$prefix = ReflectionHandler::getAnnotations($class, Prefix::class);
					if (isset($prefix[0])) {
						$descriptor->pattern = $descriptor->pattern . $prefix[0]->value;
					}
					$descriptor->holder = [$class->getName(), $method->getName()];

					$results[] = $descriptor;
				}
			}
		}

		foreach ($results as $result)
		{
			if (!isset(self::$routes[(string)$result->method]))
			{
				self::$routes[(string)$result->method] = array();
			}

			self::$routes[(string)$result->method][] = array(
				'pattern' => $result->pattern,
				'callback' => join('::', $result->holder)
			);
		}
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
		foreach (self::$routes[self::$method] as $key => $route)
		{
			if (!isset($route['pattern']))
			{
				continue;
			}

			$segments = explode('/', trim($route['pattern'] ?? "", '/'));

			if (count($segments) > 0)
			{
				self::$routes[self::$method][$key]['segment'] = array();
				self::$routes[self::$method][$key]['parameter'] = array();

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

	public static function Run()
	{
		self::$url_path = HTTPRequest::getUrlPath();
		self::$method = HTTPRequest::getRequestMethod();

		self::setSegments();

		$routes = self::$routes[self::$method];

		if (!isset($routes))
		{
			return false;
		}

		$segments = explode('/', trim(self::$url_path, '/'));

		for ($i = 0; $i < count($routes); $i++)
		{
			$route = $routes[$i];

			if (count($route['segment']) != count($segments))
			{
				return false;
			}
	
			$isValid = self::isValidRoute($route, $segments);

			if (!$isValid)
			{
				return false;
			}

			if (is_callable($route['callback']))
			{
				call_user_func_array($route['callback'], self::$arguments);
			}
			else
			{
				$method_name = explode('::', $route['callback']);
				
				if (class_exists($method_name[0]))
				{
					$caller = new $method_name[0];
					call_user_func(array($caller, $method_name[1]));
				}
			}
		}
	}

}
