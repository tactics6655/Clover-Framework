<?php

declare(strict_types=1);

namespace Neko\Classes\Reflection;

use Neko\Classes\File\Functions as FileFunctions;

use Neko\Exception\Argument\ArgumentEmptyException;

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
use ReflectionType;

class Handler
{

    public static function getStaticClassConstructor()
    {
        if (!self::hasStaticClassConstructor()) {
            return false;
        }

        return self::getMethod(static::class, '__construct');
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
     * @param \ReflectionClass|\ReflectionMethod|\ReflectionProperty $reflector
     */
    public static function hasDocumentComment(Reflector $reflector): string|false
    {
        return $reflector->getDocComment() == false;
    }

    public static function getRootDirectory(object|null $object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $directory = dirname($reflectionObject->getFileName());

        return $directory;
    }

    /**
     * Gets document comments
     * 
     * @param \ReflectionClass|\ReflectionMethod|\ReflectionProperty $reflector
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
     * @return \ReflectionClass
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
        $reflection = self::getClass($class);
        $parameters = self::getParameters($reflection);

        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && self::isNamedType($type)) {
                $name = $parameter->getClass()->newInstance();
                array_push($dependencies, $name);
            } else {
                if (!$parameter->isOptional()) {
                    throw new Exception("Can not resolve parameters");
                }
            }
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
        foreach ($traces as $key => &$trace) {
            if (!array_key_exists("class", $trace) || empty($trace)) {
                continue;
            }

            $reflectionClass = new ReflectionClass($trace['class']);

            $methods = $reflectionClass->getMethods();

            $filtered = array_filter($methods, function ($method) use ($trace) {
                return $method->name == $trace['function'];
            });
            
            $trace['absolute_file_path'] = trim(str_replace(dirname(__ROOT__), '', $trace['file'] ?? ''), "/");
            $trace['full_string'] = sprintf("%s%s%s()", $trace['short_name'] ?? "", $trace['type'], $trace['function']);

            if (empty($filtered)) {
                continue;
            }

            $method = $reflectionClass->getMethod($trace['function']);

            $shortName = $reflectionClass->getShortName();
            $trace['short_name'] = $shortName;

            $arguments = "";

            if ($method->getNumberOfParameters() > 0) {
                $parameterArguments = [];
                $parameters = $method->getParameters();
                $methodArguments = [];

                if (isset($trace['file'])) {
                    $startLine = $trace['line'] - 10 < 0 ? 0 : $trace['line'] - 10;
                    $endLine = (int)($startLine + 15);

                    $codes = explode("\r\n", htmlspecialchars(FileFunctions::read($trace['file'])));
                    if (isset($codes[$trace['line'] - 1])) {
                        $codes[$trace['line'] - 1] = "<a class='highlight'>{$codes[$trace['line'] - 1]}</a>";
                    }
                    $codes = array_slice($codes, $startLine, (int)($endLine - $startLine));

                    if (count($codes) > 1) {
                        $trace['debug_codes'] = implode("\r\n", $codes);
                    }
                }

                /** @var ReflectionParameter|ReflectionType|null, mixed[] $parameters $parameters */
                foreach ($parameters as $parameterKey => &$parameter) {
                    if ($parameter === null) {
                        continue;
                    }

                    $isArgumentExist = false;

                    $parameterArguments[$parameterKey] = new \stdClass();

                    $values = [];

                    /* Type */
                    if ($parameter->hasType()) {
                        $type = $parameter->getType()->__toString();
                        $parameterArguments[$parameterKey]->parameter_type = $type;

                        $values[] = $type;
                    }

                    /* Name */
                    $name = "$".$parameter->getName();
                    $parameterArguments[$parameterKey]->name = $parameter->getName();
                    
                    if ($parameter->isPassedByReference()) {
                        $name = "&".$name;
                    }

                    $values[] = $name;

                    /* Default Value */
                    if ($parameter->isDefaultValueAvailable() && !isset($trace['args'][$parameterKey])) {
                        $defaultValue = $parameter->getDefaultValue();
                        $parameterArguments[$parameterKey]->default_value = $defaultValue;

                        $values[] = $defaultValue;

                        $isArgumentExist = true;
                    }
                    
                    /* Arguments */
                    if (isset($trace['args'][$parameterKey])) {
                        $argumentsData = [];
                        $arguments = $trace['args'][$parameterKey];

                        $argumentMap = is_array($arguments) ? $arguments : [$arguments];

                        foreach ($argumentMap as $argument) {
                            if (is_string($argument)) {
                                $argumentsData[] = empty($argument) ? "null" : "'{$argument}'";
                            } else if (is_object($argument)) {
                                $argumentsData[] = get_class($argument) ?? ";;";
                            } else if (is_array($argument)) {
                                $argumentsData[] = "[".join(",", $argument)."]";
                            } else {
                                $argumentsData[] = "null";
                            }
                        }

                        $joinArgument = join(', ', $argumentsData);

                        if (count($argumentsData) > 1) {
                            $joinArgument = "[" . $joinArgument . "]";
                        }

                        $isArgumentExist = true;
                        $values[] = $joinArgument;
                    }

                    $values = array_map(function ($value) {
                        if (is_array($value)) {
                            return "[".join(", ", $value)."]";
                        }

                        return $value;
                    }, $values);

                    $typeArgument = (!$parameter->hasType() ?!! "" : "%s ");
                    $methodArgument = ($isArgumentExist ? "%s = %s" : "$%s");

                    $methodArguments[] = vsprintf($typeArgument.$methodArgument, $values ?? []);
                }
                
                $arguments = join(', ', $methodArguments);

                $trace['arguments'] = $arguments;
                $trace['parameters'] = $parameterArguments;
            }

            if ($method->hasReturnType()) {
                $returnType = $method->getReturnType();
                $trace['return_type'] = $returnType;
            }

            $modifier = self::getModifierString($method);
            
            $returnType = $trace['return_type'] ?? "";
            $trace['full_string'] = sprintf("%s %s%s%s(%s)".($returnType ? " : %s" : ""), $modifier, $trace['short_name'], $trace['type'], $trace['function'], $arguments ?? "", $returnType);
            
            $comment = $method->getDocComment();
            if (!$comment) {
                continue;
            }

            $comments = self::parseComments($comment);

            $trace['comment'] = implode("\r\n", array_values($comments));
        }

        return $traces;
    }

    public static function parseComments($comment)
    {
        $comments = array_slice(explode("\n", $comment), 1);
        foreach ($comments as $key => &$comment) {
            $comment = self::trimComment($comment);

            if (str_starts_with($comment, "@")) {
                unset($comments[$key]);
            } else if (empty($comment)) {
                unset($comments[$key]);
            }
        }

        return $comments;
    }

    public static function trimComment($comment)
    {
        $comment = trim($comment);
        $comment = rtrim($comment, "/");
        $comment = ltrim($comment, "*");
        $comment = trim($comment);

        return $comment;
    }

    public static function getNewInstance($class): object|bool
    {
        if (!class_exists($class)) {
            return false;
        }

        return new ($class);
    }

    /**
     * Throw argument empty errors
     */
    public static function throwEmptyParameterError($self, $function, $defineValues)
    {
        $class = self::getClass($self);

        $method = $class->getMethod($function);

        $parameters = $method->getParameters();
        $countOfRequiredParameters = $method->getNumberOfRequiredParameters();
        if ($countOfRequiredParameters == 0) {
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
            
            $arrow = "->";
            if ($method->isStatic()) {
                $arrow = "::";
            }
            $methodName = $method->getName();

            if (!in_array($name, array_keys($defineValues))) {
                continue;
            }

            $messages[] = "[{$className}{$arrow}{$methodName}] Argument #{$position} (\${$name}) cannot be empty";
        }

        throw new ArgumentEmptyException(join("\r\n", $messages));
    }

    public static function getModifierString(ReflectionMethod $method)
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
        }

        return '';
    }

    /**
     * Invokes function
     * 
     * @param object|string|null $class
     * @param array $passParameters = []
     * @param ?Closure|string $method = null
     * 
     * @return mixed
     */
    public static function invoke(object|string|null $class, array $passParameters = [], $arguments = [], null|callable|string $method = null): mixed
    {
        $reflection = $method == null ? self::getFunction($class) : self::getMethod($class, $method);
        /** @var ReflectionParameter[] $parameters */
        $parameters = self::getMethodParameters($reflection);

        $dependencies = [];

        foreach ($parameters as $parameter) {
            /** @var ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null $type */
            $type = $parameter->getType();

            if (!$type || !self::isNamedType($type)) {
                continue;
            }

            if (80100 <= PHP_VERSION_ID && self::isNamedType($type)) {
                $reflectionClass = new ReflectionClass($type->getName());
            } else if (80000 > PHP_VERSION_ID) {
                $reflectionClass = $parameter->getClass();
            }

            $dependencies[] = $reflectionClass->newInstance();
        }

        if (!is_object($class)) {
            $instanceClass = self::getClassReflection($class);
        } else {
            $instanceClass = $class;
        }

        if (isset($arguments) && !empty($arguments)) {
            $class = new ReflectionClass($instanceClass);
            $instance = $class->newInstance($arguments);

            return $reflection->invoke($instance, ...array_merge($dependencies, $passParameters));
        }

        return $reflection->invoke($instanceClass, ...$dependencies);
    }
}
