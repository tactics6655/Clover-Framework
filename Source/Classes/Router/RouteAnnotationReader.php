<?php

namespace Xanax\Classes\Router;

use Xanax\Classes\Reflection\Handler as ReflectionHandler;
use Xanax\Annotation\Route;
use Xanax\Annotation\Prefix;
use Xanax\Annotation\Middleware;

use ReflectionClass;

class RouteAnnotationReader
{

	public function __construct()
	{
	}

	/**
	 * Reads an array of annotations
	 * 
	 * @param string $className
	 * 
	 * @return array
	 */
	public function read(string $className)
	{
		$annotations = [];

		$class = new ReflectionClass($className);

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
}
