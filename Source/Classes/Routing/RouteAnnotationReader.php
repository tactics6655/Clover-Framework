<?php

namespace Neko\Classes\Routing;

use Neko\Classes\Reflection\Handler as ReflectionHandler;
use Neko\Annotation\Route;
use Neko\Annotation\Prefix;
use Neko\Annotation\Middleware;

use ReflectionClass;

class RouteAnnotationReader
{

	public function __construct()
	{
	}

	public function supplement($descriptor, $reflection)
	{
		$prefixAnnotation = ReflectionHandler::getAnnotations($reflection, Prefix::class);
		if (isset($prefixAnnotation[0])) {
			$descriptor->pattern = $prefixAnnotation[0]->value . $descriptor->pattern;
		}

		$middlewareAnnotation = ReflectionHandler::getAnnotations($reflection, Middleware::class);
		foreach ($middlewareAnnotation as $annotation) {
			$descriptor->middleware = $annotation->value;
		}
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

		$routeAnnotation = ReflectionHandler::getAnnotations($class, Route::class);
		if (isset($routeAnnotation[0])) {
			$descriptor = $routeAnnotation[0];
			$this->supplement($descriptor, $class);

			$annotations[] = $descriptor;
		}

		foreach ($class->getMethods() as $method) {
			if ($method->isStatic() || $method->isPrivate() || $method->isProtected()) {
				continue;
			}

			$routeAnnotation = ReflectionHandler::getAnnotations($method, Route::class);
			if (!isset($routeAnnotation[0])) {
				continue;
			}

			$descriptor = $routeAnnotation[0];
			$this->supplement($descriptor, $method);
			$descriptor->holder = [$class->getName(), $method->getName()];

			$annotations[] = $descriptor;
		}

		return $annotations;
	}
}
