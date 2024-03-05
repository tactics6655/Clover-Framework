<?php

namespace Clover\Classes\Routing;

use Clover\Annotation\ContentType;
use Clover\Classes\Reflection\Handler as ReflectionHandler;
use Clover\Annotation\Route;
use Clover\Annotation\Prefix;
use Clover\Annotation\Middleware;
use Clover\Annotation\NotFound;

use ReflectionMethod;
use ReflectionClass;

class RouteAnnotationReader
{
	/**
	 * Set supplement
	 * 
	 * @param Route $descriptor
	 * @param ReflectionClass|ReflectionMethod $reflection
	 * 
	 * @return void
	 */
	public function supplement($descriptor, $reflection): void
	{
		/** @var Prefix[] $prefixAnnotation */
		$prefixAnnotation = ReflectionHandler::getAnnotations($reflection, Prefix::class);
		if (isset($prefixAnnotation[0])) {
			$descriptor->pattern = sprintf("%s%s", $prefixAnnotation[0]->value, $descriptor->pattern);
		}

		/** @var ContentType[] $contentTypeAnnotation */
		$contentTypeAnnotation = ReflectionHandler::getAnnotations($reflection, ContentType::class);
		if (isset($contentTypeAnnotation[0])) {
			$descriptor->contentType = sprintf("%s", $contentTypeAnnotation[0]->value);
		}

		/** @var Middleware[] $middlewareAnnotation */
		$middlewareAnnotation = ReflectionHandler::getAnnotations($reflection, Middleware::class);
		foreach ($middlewareAnnotation as $annotation) {
			$descriptor->middleware[] = $annotation->value;
		}

		/** @var NotFound[] $notFoundAnnotation */
		$notFoundAnnotation = ReflectionHandler::getAnnotations($reflection, NotFound::class);
		if (isset($notFoundAnnotation[0])) {
			$descriptor->notFoundHandler = $notFoundAnnotation[0]->value;
		}
	}

	/**
	 * Reads an array of annotations
	 * 
	 * @param string $className
	 * 
	 * @return Route[]
	 */
	public function read(string $className): array
	{
		/** @var Route[] $annotations */
		$annotations = [];

		$class = new ReflectionClass($className);

		/** @var Route[] $routeAnnotation */
		$routeAnnotation = ReflectionHandler::getAnnotations($class, Route::class);
		if (isset($routeAnnotation[0])) {
			$descriptor = $routeAnnotation[0];
			$this->supplement($descriptor, $class);

			$annotations[] = $descriptor;
		}

		/** @var ReflectionMethod[] $methods */
		$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED);
		foreach ($methods as $method) {
			if (!$class->hasMethod($method->getName())) {
				continue;
			}

			if ($method->isDestructor() || $method->isConstructor()) {
				continue;
			}

			if ($method->isStatic() || $method->isPrivate() || $method->isProtected()) {
				continue;
			}

			/** @var Route[] $routeAnnotation */
			$routeAnnotation = ReflectionHandler::getAnnotations($method, Route::class);
			if (!isset($routeAnnotation[0])) {
				continue;
			}

			/** @var Route $descriptor */
			$descriptor = $routeAnnotation[0];
			$this->supplement($descriptor, $class);
			$this->supplement($descriptor, $method);
			$descriptor->holder = [$class->getName(), $method->getName()];

			$annotations[] = $descriptor;
		}

		return $annotations;
	}
}
