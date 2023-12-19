<?php

declare(strict_types=1);

namespace Xanax\Classes\Reflection;

use ReflectionClass;
use Reflector;

class Handler 
{

    public static function getClassMethods(ReflectionClass $class)
    {
        return $class->getMethods();
    }

    public static function isSubClassOf(ReflectionClass $class, string $className)
    {
        return $class->isSubclassOf($className);
    }

    public static function isInterfaceClassDescriptor(ReflectionClass $class)
    {
        return $class->isInterface();
    }

    public static function isAbstractClassDescriptor(ReflectionClass $class)
    {
        return $class->isAbstract();
    }

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
	
    public static function class($class)
    {
        $reflection = new ReflectionClass($class);

        return $reflection;
    }

    public static function method($class, $method)
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
		$reflection = self::class($class);
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

	public static function invoke($class, $method, $append_parameters = [], $arguments = [])
	{
		$reflection = self::method($class, $method);
		$parameters = self::getMethodParameters($reflection);

		$dependencies = [];

		foreach ($parameters as $parameter) 
        {
			$type = $parameter->getType();

			if ($type && self::isNamedType($type)) 
            {
                if (8 === PHP_MAJOR_VERSION) 
                {
                    $reflectionClass = new ReflectionClass($parameter->getType()->getName());

                    array_push($dependencies, $reflectionClass->newInstance());
                }
                else
                {
                    $name = $parameter->getClass()->newInstance();
                    array_push($dependencies, $name);
                }
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

        if (isset($arguments) && !empty($arguments))
        {
            $class = new ReflectionClass($initClass);
            $instance = $class->newInstance($arguments);

            return $reflection->invoke($instance, ...array_merge($dependencies, $append_parameters));
        }

		return $reflection->invoke($initClass, ...$dependencies);
	}

}