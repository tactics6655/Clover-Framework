<?php

declare(strict_types=1);

namespace Clover\Classes\Reflection;

use Clover\Classes\File\Functions as FileFunctions;
use Clover\Classes\Debug\TraceObject;
use Clover\Classes\DependencyInjection\Container;

use Clover\Exception\Argument\ArgumentEmptyException;

use stdClass;
use Closure;
use Exception;
use Reflector;
use Reflection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionUnionType;
use ReflectionIntersectionType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;
use ReflectionObject;
use SensitiveParameter;
use __PHP_Incomplete_Class;
use ArrayIterator;
use Throwable;

class Handler
{

    private static $magicMethods = ['construct', 'call', 'callStatic', 'toString', 'invoke', 'set_state', 'set', 'isset', 'unset', 'clone', 'serialize', 'unserialize', 'sleep', 'wakeup', 'destruct', 'get'];

    private static $scalarTypes = ['bool', 'int', 'float', 'string'];

    private static $valueTypes = ['true', 'false'];

    private static $relativeClassTypes = ['self', 'parent', 'static'];

    private static $builtInTypes = ['true', 'false', 'bool', 'int', 'float', 'string', 'null', 'array', 'object', 'resource', 'never', 'void'];

    private static $atomicTypes = ['true', 'false', 'bool', 'int', 'float', 'string', 'null', 'array', 'object', 'resource', 'never', 'void', 'self', 'parent', 'static'];

    public static function isRelativeClassType($type)
    {
        return isset(self::$relativeClassTypes[$type]);
    }

    public static function isScalarType($type)
    {
        return isset(self::$scalarTypes[$type]);
    }

    public static function isValueType($type)
    {
        return isset(self::$valueTypes[$type]);
    }

    public static function isAtomicTypes($type)
    {
        return isset(self::$atomicTypes[$type]);
    }

    public static function isBuiltInTypes($type)
    {
        return isset(self::$builtInTypes[$type]);
    }

    public static function getClassPropertieNames(string $class): array
    {
        $reflectionClass = new ReflectionClass($class);
        $properties = $reflectionClass->getProperties();
        $names = [];

        foreach ($properties as $property) {
            $names[] = $property->getName();
        }

        return $names;
    }

    public static function hasAttribute(string $class, string $attributeName): bool
    {
        $reflectionClass = new ReflectionClass($class);

        foreach ($reflectionClass->getAttributes($attributeName) as $attribute) {
            if ($attribute->getName() === $attributeName) {
                return true;
            }
        }

        return false;
    }

    public static function isIncompleteClass(mixed $class)
    {
        return ($class instanceof __PHP_Incomplete_Class);
    }

    public static function getAvailableMagicMethodsInParentClass(object|string $objectOrClass)
    {
        $methods = [];
        $parent = get_parent_class($objectOrClass);

        foreach (self::$magicMethods as $method) {
            $methodName = sprintf("__%s", $method);

            if (!$parent || !method_exists($parent, $methodName)) {
                continue;
            }

            $reflectionMethod = new ReflectionMethod($parent, $methodName);
            if ($reflectionMethod->isAbstract() || $reflectionMethod->isPrivate()) {
                continue;
            }

            $methods = $methodName;
        }

        return $methods;
    }

    public static function getStaticClassConstructor()
    {
        if (!self::hasStaticClassConstructor()) {
            return false;
        }

        return self::getMethod(static::class, '__construct');
    }

    public static function isCloneableClass(ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->isCloneable() && !$reflectionClass->hasMethod('__clone') && !$reflectionClass->isSubclassOf(ArrayIterator::class);
    }

    public static function hasInternalAncestors(ReflectionClass $reflectionClass): bool
    {
        do {
            if ($reflectionClass->isInternal()) {
                return true;
            }

            $reflectionClass = $reflectionClass->getParentClass();
        } while ($reflectionClass);

        return false;
    }

    /**
     * Returns an array of all declared traits
     */
    public static function getDeclaredTraits(): array
    {
        return get_declared_traits();
    }

    /**
     * Returns an array with the name of the defined classes
     */
    public static function getDeclaredClasses(): array
    {
        return get_declared_classes();
    }

    /**
     * Returns an array of all defined functions
     */
    public static function getDeclaredFunctions(): array
    {
        return get_defined_functions();
    }

    public static function getMatchedNameSpacesFromDeclaredClasses(string $class): array
    {
        return array_filter(self::getDeclaredClasses(), function ($classes) use ($class) {
            $split = explode("\\", $classes);
            return $split[count($split) - 1] === $class;
        });
    }

    public static function getCallMethodFromString($callback): array
    {
        if (self::isStaticMethodString($callback)) {
            [$class, $method] = explode('::', $callback);
        } else if (is_array($callback)) {
            [$class, $method] = $callback;
        }

        if (count(explode("\\", $class)) == 1) {
            $matchNamespace = self::getMatchedNameSpacesFromDeclaredClasses($class);

            if (count($matchNamespace) === 1) {
                $class = array_pop($matchNamespace);
            }
        }

        return [$class, $method];
    }

    public function toString(Reflector $reflector): ?string
    {
        if ($reflector instanceof ReflectionClass) {
            return $reflector->name;
        }

        if ($reflector instanceof ReflectionMethod) {
            return sprintf("%s::%s()", $reflector->getDeclaringClass()->name, $reflector->name);
        }

        if ($reflector instanceof ReflectionFunction) {
            return sprintf("%s()", $reflector->name);
        }

        if ($reflector instanceof ReflectionProperty) {
            return self::getPropertyDeclaringClass($reflector)->name . '::$' . $reflector->name;
        }

        if ($reflector instanceof ReflectionParameter) {
            return sprintf("$%s in %s", $reflector->name, self::toString($reflector->getDeclaringFunction()));
        }

        return null;
    }

    public static function getPropertyDeclaringClass(ReflectionProperty $property): ReflectionClass
    {
        $name = $property->name;
        $declaringClass = $property->getDeclaringClass();

        foreach ($declaringClass->getTraits() as $trait) {
            if (trait_exists($trait->getName()) && $trait->hasProperty($name) && $trait->getProperty($name)->getDocComment() === $property->getDocComment()) {
                return self::getPropertyDeclaringClass($trait->getProperty($name));
            }
        }

        return $declaringClass;
    }

    public static function isStaticMethodString($method)
    {
        return !is_callable($method) && is_string($method) && !empty($method) && strpos($method, '::') > 0 && str_contains($method, '::');
    }

    /**
     * Checks if the class method exists
     */
    public static function isMethodExists($object_or_class, string $method)
    {
        return method_exists($object_or_class, $method);
    }

    public static function hasStaticClassConstructor()
    {
        return method_exists(static::class, '__construct');
    }

    /**
     * Gets an array of methods for the class.
     * 
     * @param ReflectionClass $class
     * 
     * @return array
     */
    public static function getClassMethods(ReflectionClass $class): array
    {
        return $class->getMethods();
    }

    public static function isInterfaceExists($interface, $autoload = false)
    {
        return interface_exists($interface, $autoload);
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
     * Check if has document comments
     * 
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflector
     */
    public static function hasDocumentComment(Reflector $reflector): string|false
    {
        return $reflector->getDocComment() == false;
    }

    public static function getRootDirectory(object|null $object)
    {
        $reflectionObject = new ReflectionObject($object);
        $directory = dirname($reflectionObject->getFileName());

        return $directory;
    }

    /**
     * Gets document comments
     * 
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflector
     */
    public static function getDocumentComment(Reflector $reflector): string|false
    {
        $rawComment = $reflector->getDocComment();
        if (!$rawComment) {
            return false;
        }

        $comments = explode("\n", $rawComment);

        foreach ($comments as &$comment) {
            $comment = ltrim($comment);
            $comment = ltrim($comment, "*");
        }

        return $comments;
    }

    /**
     * Gets an array of annotation for the class
     * 
     * @param ReflectionClass|ReflectionMethod $reflector
     * @param string $annotationName
     * 
     * @return array
     */
    public static function getAnnotations(ReflectionClass|ReflectionMethod $reflector, string $annotationName): array
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
     * @return ReflectionClass
     */
    public static function getClass(object|string $class): ReflectionClass
    {
        $reflection = new ReflectionClass($class);

        return $reflection;
    }

    /**
     * Get a ReflectionFunction
     * 
     * @param Closure|string $function
     * 
     * @return ReflectionFunction
     */
    public static function getFunction(Closure|string $function): ReflectionFunction
    {
        $reflection = new ReflectionFunction($function);

        return $reflection;
    }

    /**
     * Get a ReflectionMethod
     * 
     * @param object|string|null $class
     * @param string|null $method = null
     * 
     * @return ReflectionMethod
     */
    public static function getMethod(object|string|null $class, string|null $method = null): ReflectionMethod
    {
        $reflection = new ReflectionMethod($class, $method);

        return $reflection;
    }

    /**
     * Gets an parameters of constructor
     * 
     * @param ReflectionClass $reflection
     * 
     * @return ReflectionParameter[]
     */
    public static function getParameters(ReflectionClass $reflection)
    {
        $parameters = $reflection->getConstructor() ? $reflection->getConstructor()->getParameters() : [];

        return $parameters;
    }

    /**
     * Gets parameters
     * 
     * @param ReflectionFunction|ReflectionMethod $reflection
     * 
     * @return array
     */
    public static function getMethodParameters(ReflectionFunction|ReflectionMethod $reflection): array
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
        return $parameter instanceof ReflectionNamedType;
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
        $dependencies = [];

        $reflection = self::getClass($class);
        $parameters = self::getParameters($reflection);
        
        foreach ($parameters as $parameter) {
            /** @var ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null $type */
            $type = $parameter->getType();

            if (!$type || !self::isNamedType($type)) {
                continue;
            }

            if ($parameter->isOptional()) {
                continue;
            }

            if (80100 <= PHP_VERSION_ID) {
                $name = $type->getName();
                $instance = new ReflectionClass($name);
            } else if (80000 > PHP_VERSION_ID) {
                $instance = $parameter->getClass()->newInstance();
            }

            $dependencies[] = $instance;
        }

        return $reflection->newInstance(...$dependencies);
    }

    /**
     * Retrieves the parent class name for object or class
     * 
     * @return string|false
     */
    public static function getParentClass($object)
    {
        return get_parent_class($object);
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
        $reflection = self::getMethod($class, $method);

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
        $reflection = self::getMethod($controller, $method);

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
     * Parse exception traces
     * 
     * @param array $traces
     * 
     * @return array
     */
    public static function parseTrace(array $traces)
    {
        $traceObjects = [];

        foreach ($traces as $trace) {
            if (!array_key_exists("class", $trace) || empty($trace)) {
                continue;
            }

            $traceObjects[] = new TraceObject($trace);
        }

        return $traceObjects;
    }

    /**
     * Get a new instance of class
     * 
     * @param string $class
     * 
     * @return object|bool
     */
    public static function getNewInstance(string $class): object|bool
    {
        if (!class_exists($class)) {
            return false;
        }

        return new ($class);
    }

    /**
     * Throw argument empty errors
     * 
     * @param object|string $self
     * @param string $function
     * @param array $defineValues
     * 
     * @return void|bool
     */
    public static function throwEmptyParameterError(object|string $self, string $function, array $defineValues)
    {
        $class = self::getClass($self);

        $method = $class->getMethod($function);

        $parameters = $method->getParameters();
        $countOfRequiredParameters = $method->getNumberOfRequiredParameters();
        if ($countOfRequiredParameters === 0) {
            return false;
        }

        $requiredParameters = [];
        foreach ($parameters as $parameter) {
            $isDefaultValueAvailable = $parameter->isDefaultValueAvailable();

            if ($isDefaultValueAvailable) {
                continue;
            }

            if ($parameter->allowsNull()) {
                continue;
            }

            if ($parameter->isOptional()) {
                continue;
            }

            $requiredParameters[] = $parameter;
        }

        $messages = [];
        foreach ($requiredParameters as $parameter) {
            $position = $parameter->getPosition() + 1;
            $name = $parameter->getName();
            $className = $class->getName();
            $methodName = $method->getName();

            $arrow = "->";
            if ($method->isStatic()) {
                $arrow = "::";
            }

            if (!in_array($name, array_keys($defineValues))) {
                continue;
            }

            $messages[] = "[{$className}{$arrow}{$methodName}] Argument #{$position} (\${$name}) cannot be empty";
        }

        throw new ArgumentEmptyException(join("\r\n", $messages));
    }

    /**
     * Gets an attributes of sensitive parameter
     * 
     * @param ReflectionParameter $param
     * 
     * @return array
     */
    public static function getSensitiveParameterAttributes(ReflectionParameter $param): array
    {
        return $param->getAttributes(SensitiveParameter::class);
    }

    /**
     * Convert class modifiers to string
     * 
     * @param ReflectionClass $class
     * 
     * @return string
     */
    public static function getClassModifierString(ReflectionClass $class): string
    {
        $modifier = $class->getModifiers();

        switch ($modifier) {
            case ReflectionProperty::IS_READONLY:
                return 'readonly';
            case ReflectionProperty::IS_PRIVATE:
                return 'private';
            case ReflectionProperty::IS_PUBLIC:
                return 'public';
            case ReflectionProperty::IS_PROTECTED:
                return 'protected';
            case ReflectionProperty::IS_STATIC:
                return 'static';
            default:
                return 'unknown';
        }
    }

    /**
     * Convert method modifiers to string
     * 
     * @param ReflectionMethod $method
     * 
     * @return string
     */
    public static function getMethodModifierString(ReflectionMethod $method): string
    {
        $modifier = $method->getModifiers();

        switch ($modifier) {
            case ReflectionMethod::IS_FINAL:
                return 'final';
            case ReflectionMethod::IS_PRIVATE:
                return 'private';
            case ReflectionMethod::IS_PUBLIC:
                return 'public';
            case ReflectionMethod::IS_ABSTRACT:
                return 'abstract';
            case ReflectionMethod::IS_PROTECTED:
                return 'protected';
            case ReflectionMethod::IS_STATIC:
                return 'static';
            default:
                return 'unknown';
        }
    }

    /**
     * Gets an dependencies of method
     * 
     * @param ReflectionFunction|ReflectionMethod $reflection
     * @param null|Container|array $definedDependencies
     * 
     * @return array
     */
    public static function getDependencies(ReflectionFunction|ReflectionMethod $reflection, null|Container|array $definedDependencies = null): array
    {
        $dependencies = [];

        /** @var ReflectionParameter[] $parameters */
        $parameters = self::getMethodParameters($reflection);

        foreach ($parameters as $parameter) {
            /** @var ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null $type */
            $type = $parameter->getType();

            if (!$type || !self::isNamedType($type)) {
                continue;
            }

            $name = $type->getName();

            if ($definedDependencies instanceof Container && $dependency = $definedDependencies->getByType($name)) {
                $dependencies[] = $dependency;

                continue;
            }

            if (!class_exists($name)) {
                continue;
            }

            if (80100 <= PHP_VERSION_ID) {
                $reflectionClass = new ReflectionClass($name);
            } else if (80000 > PHP_VERSION_ID) {
                $reflectionClass = $parameter->getClass();
            }

            $instance = $reflectionClass->newInstance();
            if (!$reflectionClass->isInstance($instance)) {
                continue;
            }

            $dependencies[] = $instance;
        }

        return $dependencies;
    }

    /**
     * Invokes function
     * 
     * @param object|string|null $instance
     * @param ?Closure|string $method = null
     * @param array $passParameters = []
     * 
     * @return mixed
     */
    public static function invoke(object|string|null $instance, null|callable|string $method = null, array $passParameters = [], Container|array $arguments = []): mixed
    {
        $reflection = $method == null ? self::getFunction($instance) : self::getMethod($instance, $method);
        $dependencies = self::getDependencies($reflection, $arguments);

        if (!is_object($instance) && is_string($instance)) {
            $newInstance = self::getClassReflection($instance);
        }

        if (isset($arguments) && !empty($arguments)) {
            $instance = new ReflectionClass($newInstance ?? $instance);
            $newInstance = $instance->newInstance($arguments);

            if (!$instance->isInstance($newInstance)) {
                return false;
            }

            $dependencies = [...$dependencies, ...array_filter($passParameters)];
        }

        return $reflection->invoke($newInstance, ...$dependencies);
    }
}
