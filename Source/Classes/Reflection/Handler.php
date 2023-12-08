<?php

declare(strict_types=1);

namespace Xanax\Classes\Reflection;

class Handler 
{

    public static function getAnnotations(Reflector $reflector, string $annotationName) : array
    {
        $result = [];

        if (8 === PHP_MAJOR_VERSION) {
            $attributes = $reflector->getAttributes($annotationName);
            foreach ($attributes as $attribute) {
                $result[] = $attribute->newInstance();
            }
        }

        return $result;
    }
	
    public static function Class($class)
    {
        $reflection = new \ReflectionClass($class);

        return $reflection;
    }

    public static function Method($class, $method)
    {
        $reflection = new \ReflectionMethod($class, $method);

        return $reflection;
    }

    public static function getParameters($reflection)
    {
        $parameters = $reflection->getConstructor() ? $reflection->getConstructor()->getParameters() : [];

        return $parameters;
    }

    public static function getMethodParameters($reflection)
    {
        $parameters = $reflection->getParameters();

        return $parameters;
    }

    public static function isNamedType($parameter) 
    {
        return $parameter instanceof \ReflectionNamedType;
    }

	public static function getClassReflection($class)
	{
		$reflection = self::Class($class);
		$parameters = self::getParameters($reflection);

		$dependencies = [];

		foreach ($parameters as $parameter) 
        {
			$type = $parameter->getType();

			if ($type && self::isNamedType($type)) 
            {
				$name = $parameter->getClass()->newInstance();
				array_push($dependencies, $name);
			} 
            else 
            {
				if (!$parameter->isOptional()) 
                {
					throw new Exception("Can not resolve parameters");
				}
			}
		}

		return $reflection->newInstance(...$dependencies);
	}

	public static function Invoke($class, $method)
	{
		$reflection = self::Method($class, $method);
		$parameters = self::getMethodParameters($reflection);

		$dependencies = [];
		foreach ($parameters as $parameter) 
        {
			$type = $parameter->getType();

			if ($type && self::isNamedType($type)) 
            {
				$name = $parameter->getClass()->newInstance();
				array_push($dependencies, $name);
			} 
            else 
            {
				$name = $parameter->getName();
			}
		}

		if (!is_object($class)) 
        {
			$initClass = self::getClassReflection($class);
		} 
        else 
        {
			$initClass = $class;
		}

		$reflection->invoke($initClass, ...$dependencies);
	}

}