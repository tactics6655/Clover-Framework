<?php

declare(strict_types=1);

namespace Xanax\Classes\Reflection;

use ReflectionClass;

class Handler
{

    /**
     * Gets an array of methods for the class.
     * 
     * @param ReflectionClass $class@param 
     * 
     * @return array
     */
    public static function getClassMethods(ReflectionClass $class): array
    {
        return $class->getMethods();
    }

    /**
     * Checks if a subclass
     * 
     * @param ReflectionClass $class
     * @param ReflectionClass|string $className
     * 
     * @return bool
     */
    public static function isSubClassOf(ReflectionClass $class, ReflectionClass|string $className): bool
    {
        return $class->isSubclassOf($className);
    }

    /**
     * Checks if the class is an interface
     * 
     * @param ReflectionClass $class
     * 
     * @return bool
     */
    public static function isInterfaceClassDescriptor(ReflectionClass $class): bool
    {
        return $class->isInterface();
    }

    /**
     * Checks if class is abstract
     * 
     * @param ReflectionClass $class
     * 
     * @return bool
     */
    public static function isAbstractClassDescriptor(ReflectionClass $class): bool
    {
        return $class->isAbstract();
    }

    /**
     * Gets an array of annotation for the class
     * 
     * @param ReflectionClass|\ReflectionMethod $reflector
     * @param string $annotationName
     * 
     * @return array
     */
    public static function getAnnotations(ReflectionClass|\ReflectionMethod $reflector, string $annotationName): array
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

    /**
     * Get a ReflectionClass
     * 
     * @param object|string $class
     * 
     * @return \ReflectionClass
     */
    public static function class(object|string $class): ReflectionClass
    {
        $reflection = new ReflectionClass($class);

        return $reflection;
    }

    /**
     * Get a ReflectionFunction
     * 
     * @param \Closure|string $function
     * 
     * @return \ReflectionFunction
     */
    public static function function(\Closure|string $function): \ReflectionFunction
    {
        $reflection = new \ReflectionFunction($function);

        return $reflection;
    }

    /**
     * Get a ReflectionMethod
     * 
     * @param object|string|null $class
     * @param string|null $method = null
     * 
     * @return \ReflectionMethod
     */
    public static function method(object|string|null $class, string|null $method = null): \ReflectionMethod
    {
        $reflection = new \ReflectionMethod($class, $method);

        return $reflection;
    }

    /**
     * Gets an parameters of constructor
     * 
     * @param ReflectionClass $reflection
     * 
     * @return array
     */
    public static function getParameters(ReflectionClass $reflection)
    {
        $parameters = $reflection->getConstructor() ? $reflection->getConstructor()->getParameters() : [];

        return $parameters;
    }

    /**
     * Gets parameters
     * 
     * @param \ReflectionFunction|\ReflectionMethod $reflection
     * 
     * @return array
     */
    public static function getMethodParameters(\ReflectionFunction|\ReflectionMethod  $reflection): array
    {
        $parameters = $reflection->getParameters();

        return $parameters;
    }

    /**
     * Checks if type of reflection is ReflectionNamedType
     * 
     * @param mixed $parameter
     * 
     * @return bool
     */
    public static function isNamedType(mixed $parameter): bool
    {
        return $parameter instanceof \ReflectionNamedType;
    }

    /**
     * Get a new class instance
     * 
     * @param object|string $class
     * 
     * @return object
     */
    public static function getClassReflection(object|string $class): object
    {
        $reflection = self::class($class);
        $parameters = self::getParameters($reflection);

        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && self::isNamedType($type)) {
                $name = $parameter->getClass()->newInstance();
                array_push($dependencies, $name);
            } else {
                if (!$parameter->isOptional()) {
                    throw new \Exception("Can not resolve parameters");
                }
            }
        }

        return $reflection->newInstance(...$dependencies);
    }

    /**
     * Call the callback given by the first parameter
     * 
     * @param callable $method
     * @param array ...$arguments
     * 
     * @return mixed
     */
    public static function callMethod(callable $method, array ...$arguments): mixed
    {
        return call_user_func($method, $arguments);
    }

    /**
     * Call a callback with an array of parameters
     * 
     * @param callable $method
     * @param array $arguments = []
     * 
     * @return mixed
     */
    public static function callMethodArray(callable $method, array $arguments = []): mixed
    {
        return call_user_func_array($method, $arguments);
    }

    /**
     * Call the callback given by the first parameter
     * 
     * @param object|string|null $class
     * @param string $method
     * @param array ...$arguments
     * 
     * @return mixed
     */
    public static function callMethodOfClass(object|string|null $class, string $method, array ...$arguments): mixed
    {
        $reflection = self::method($class, $method);

        if ($reflection->isStatic()) {
            return forward_static_call($method, $arguments);
        }

        return call_user_func($method, $arguments);
    }

    /**
     * Call a callback with an array of parameters
     * 
     * @param object|string|null $controller
     * @param string $method
     * @param array $arguments = []
     * 
     * @return mixed
     */
    public static function callArrayMethodOfClass(object|string|null $controller, string $method, array $arguments = [])
    {
        $reflection = self::method($controller, $method);

        if ($reflection->isStatic()) {
            return forward_static_call_array($method, $arguments);
        }

        return call_user_func_array($method, $arguments);
    }

    /**
     * Verify that the contents of a variable can be called as a function
     * 
     * @param mixed $value
     * @param bool $syntax_only = false
     * @param null|string &$callable_name = null
     * 
     * @return bool
     */
    public static function isCallable(mixed $value, bool $syntax_only = false, null|string &$callable_name = null): bool
    {
        return is_callable($value, $syntax_only, $callable_name);
    }

    /**
     * Gets the class methods' names
     * 
     * @param object|string $object_or_class
     * 
     * @return array
     */
    public static function getClassMethodNames(object|string $object_or_class): array
    {
        return get_class_methods($object_or_class);
    }

    /**
     * Invokes function
     * 
     * @param object|string|null $class
     * @param array $passParameters = []
     * @param ?\Closure|string $method = null
     * 
     * @return mixed
     */
    public static function invoke(object|string|null $class, array $passParameters = [], $arguments = [], null|callable|string $method = null): mixed
    {
        $reflection = $method == null ? self::function($method) : self::method($class, $method);
        /** @var \ReflectionParameter[] $parameters */
        $parameters = self::getMethodParameters($reflection);

        $dependencies = [];

        foreach ($parameters as $parameter) {
            /** @var \ReflectionNamedType|\ReflectionUnionType|\ReflectionIntersectionType|null $type */
            $type = $parameter->getType();

            if (!$type || !self::isNamedType($type)) {
                continue;
            }

            if (8 === PHP_MAJOR_VERSION) {
                $reflectionClass = new ReflectionClass($type->getName());
            } else {
                $reflectionClass = $parameter->getClass();
            }

            array_push($dependencies, $reflectionClass->newInstance());
        }

        if (!is_object($class)) {
            $initClass = self::getClassReflection($class);
        } else {
            $initClass = $class;
        }

        if (isset($arguments) && !empty($arguments)) {
            $class = new ReflectionClass($initClass);
            $instance = $class->newInstance($arguments);

            return $reflection->invoke($instance, ...array_merge($dependencies, $passParameters));
        }

        return $reflection->invoke($initClass, ...$dependencies);
    }
}
