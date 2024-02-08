<?php

declare(strict_types=1);

namespace Neko\Classes\Debug;

use Neko\Classes\File\Functions as FileFunctions;

use ReflectionClass;
use ReflectionParameter;
use ReflectionType;
use ReflectionNamedType;
use ReflectionUnionType;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionAttribute;

class TraceObject
{
    protected ?ReflectionClass $declaring_class = null;

    protected ?string $function = null;

    protected ?string $class = null;

    protected ?string $file = null;

    protected ?string $short_name = null;

    protected ?string $type = null;

    protected ?int $line = null;

    /** @var mixed[] $args */
    private ?array $args = null;

    /** @var string[] $comments */
    protected ?array $comments = null;

    protected ?string $text = null;

    protected ?string $code = null;

    /** @var TraceArgumentObject[] $arguments */
    protected array $arguments = [];

    protected ?string $annotation = null;

    protected ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null $return_type = null;

    public function __construct(array $trace)
    {
        $this->setFunction($trace['function'] ?? null);
        $this->class = $trace['class'] ?? null;
        $this->file = $trace['file'] ?? null;
        $this->type = $trace['type'] ?? null;
        $this->line = $trace['line'] ?? null;
        $this->args = $trace['args'] ?? null;

        if ($this->hasFunction()) {
            $this->parseClass();
        }
    }

    public function hasArguments() :bool
    {
        return isset($this->arguments);
    }

    public function getArguments() :?array
    {
        return $this->arguments;
    }

    public function hasReturnType() :bool
    {
        return isset($this->return_type);
    }

    public function setReturnType($return_type) :void
    {
        $this->return_type = $return_type;
    }

    /**
     * Get return type
     * 
     * @return ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null
     */
    public function getReturnType() :ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null
    {
        return $this->return_type;
    }

    public function hasFile() :bool
    {
        return isset($this->file);
    }

    public function getFile() :?string
    {
        return $this->file;
    }

    public function hasShortName() :bool
    {
        return isset($this->short_name);
    }

    public function setShortName($short_name) :void
    {
        $this->short_name = $short_name;
    }

    public function getShortName() :?string
    {
        return $this->short_name;
    }

    public function hasType() :bool
    {
        return isset($this->type);
    }

    public function getType() :?string
    {
        return $this->type;
    }

    public function hasLine() :bool
    {
        return isset($this->line);
    }

    public function getLine() :?int
    {
        return $this->line;
    }

    public function hasText() :bool
    {
        return isset($this->text);
    }

    public function setText($text) :void
    {
        $this->text = $text;
    }

    public function getText() :?string
    {
        return $this->text;
    }

    public function hasClass() :bool
    {
        return isset($this->class);
    }

    /**
     * Get class
     * 
     * @return string
     */
    public function getClass() :?string
    {
        return $this->class;
    }

    /**
     * Check that if code exists
     * 
     * @return bool
     */
    public function hasCode() :bool
    {
        return isset($this->code);
    }

    /**
     * Get code
     * 
     * @return string
     */
    public function getCode() :string
    {
        return $this->code;
    }

    /**
     * Check that if comment exists
     * 
     * @return bool
     */
    public function hasComment() :bool
    {
        return isset($this->comments);
    }

    /**
     * Get parsed comment list
     * 
     * @return string[]
     */
    public function getComment() :?array
    {
        return $this->comments;
    }

    /**
     * Get comment of document
     * 
     * @return string
     */
    public function getCommentTag() :string
    {
        return join("\r\n", $this->comments);
    }

    public function hasAnnotation() :bool
    {
        return isset($this->annotation) && !empty($this->annotation);
    }

    /**
     * Get annotation
     * 
     * @return string
     */
    public function getAnnotation() :?string
    {
        return $this->annotation;
    }

    public function setFunction($function) :void
    {
        $this->function = $function;
    }

    public function hasFunction() :bool
    {
        return isset($this->function) && !empty($this->function);
    }

    public function getFunction() :?string
    {
        return $this->function;
    }
    
    public function hasDeclaringClass() :bool
    {
        return isset($this->declaring_class);
    }

    public function setDeclaringClass($declaring_class) :void
    {
        $this->declaring_class = $declaring_class;
    }

    /**
     * Get declaring class
     * 
     * @return string
     */
    public function getDeclaringClass() :?string
    {
        return $this->declaring_class;
    }

    private function parseClass() :void
    {
        $reflectionClass = new ReflectionClass($this->class);
        $shortName = $reflectionClass->getShortName();

        $this->setShortName($shortName);
        $this->setText(sprintf("%s%s%s()", $this->getShortName() ?? "", $this->getTYpe(), $this->getFunction()));

        $methods = $reflectionClass->getMethods();
        if (!$this->isMethodExist($methods)) {
            return;
        }

        $this->parseCode();

        $objectArguments = [];
        $method = $reflectionClass->getMethod($this->getFunction());
        if ($method->getNumberOfParameters() >= 0) {
            $objectArguments = $this->parseMethodArguments($method);
        }

        if ($method->hasReturnType()) {
            $returnType = $method->getReturnType();
            $this->setReturnType($returnType);
        }

        $arguments = [];
        foreach ($objectArguments as $argument) {
            $this->arguments[] = $argument;
            
            if (!$argument->hasArguments()) {
                continue;
            }

            $arguments[] = $argument->getArguments();
        }
        $arguments = $this->argumentToString($arguments);

        $modifier = self::getModifierString($method);
        $this->setText(sprintf("%s %s%s%s(%s)" . ($this->getShortName() ? " : %s" : ""), $modifier, $this->getShortName(), $this->getType(), $this->getFunction(), $arguments ?? "", $this->getReturnType() ?? ""));
        $this->parseMethodComment($method);

        $annotations = [];
        $this->setDeclaringClass($method->getDeclaringClass());
        /** @var ReflectionAttribute[] $attributes */
        $attributes = $method->getAttributes();
        foreach ($attributes as $attribute) {
            $arguments = $attribute->getArguments();
            $name = $attribute->getName();
            $target = $attribute->getTarget();

            $annotations[] = sprintf("#[%s[%s]]:%s", $name,  join(", ", array_map(function ($data) {
                return isset($data) ? sprintf("'%s'", $data) : null;
            }, $arguments)), $target);
        }

        $this->annotation = join("\r\n", $annotations);
    }

    private function parseMethodComment(ReflectionMethod $method)
    {
        $comment = $method->getDocComment();
        if (!$comment) {
            return;
        }

        $comments = self::parseComments($comment);
        $this->comments = $comments;
    }

    private function isMethodExist(array $methods) :bool
    {
        $existMethods = array_filter($methods, function ($method) {
            return $method->name == $this->getFunction();
        });

        return !empty($existMethods);
    }

    private function parseCode() :void
    {
        if (!$this->hasFile()) {
            return;
        }

        $startLine = $this->getLine() - 10 < 0 ? 0 : $this->getLine() - 10;
        $endLine = (int)($startLine + 15);

        $readedContent = FileFunctions::read($this->file);
        
        $codes = explode("\r\n", htmlspecialchars($readedContent));
        if (isset($codes[$this->line - 1])) {
            $codes[$this->line - 1] = "<a class='highlight'>{$codes[$this->line - 1]}</a>";
        }

        $codes = array_slice($codes, $startLine, (int)($endLine - $startLine));
        if (count($codes) > 1) {
            $this->code = implode("\r\n", $codes);
        }
    }

    /**
     * @return TraceArgumentObject[]
     */
    private function parseMethodArguments(ReflectionMethod $method) :array
    {
        $returnArguments = [];
        $parameters = $method->getParameters();

        /** @var ReflectionParameter|ReflectionType|null[] $parameters */
        foreach ($parameters as $key => &$parameter) {
            $values = [];
            $isArgumentExist = false;

            if ($parameter === null) {
                continue;
            }

            $traceArgumentObject = new TraceArgumentObject();
            if ($parameter->hasType()) {
                /** @var ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null $type */
                $type = $parameter->getType();
                $traceArgumentObject->setType($type->__toString());
                $values[] = $type;
            }

            $name = $parameter->getName();
            $traceArgumentObject->setName($name);
            $traceArgumentObject->setPassedByReference($parameter->isPassedByReference());
            $values[] = $name;

            if ($parameter->isDefaultValueAvailable() && !isset($this->args)) {
                $isArgumentExist = true;

                $defaultValue = $parameter->getDefaultValue();
                $traceArgumentObject->setDefaultValue($defaultValue);
                $values[] = $defaultValue;
            } else if (isset($this->args)) {
                $isArgumentExist = true;

                $arguments = $this->args[$key] ?? null;
                $parsedArguments = $this->parseArgument($arguments);
                $joinArgument = join(', ', $parsedArguments);
                $values[] = $joinArgument;
            }

            $typeFormat = (!$parameter->hasType() ? !!"" : "%s ");
            $methodFormat = ($isArgumentExist ? "%s = %s" : "$%s");
            $traceArgumentObject->setArguments(vsprintf($typeFormat . $methodFormat, $values ?? []));
            $returnArguments[] = $traceArgumentObject;
        }

        return $returnArguments;
    }

    /**
     * @param array|object|string|null $arguments
     * 
     * @return array
     */
    private function parseArgument(array|object|string|null $arguments) :array
    {
        $parsedArguments = [];

        $map = is_array($arguments) ? $arguments : [$arguments];
        foreach ($map as $argument) {
            if (is_string($argument)) {
                $parsedArguments[] = empty($argument) ? "null" : "'{$argument}'";
                continue;
            }

            if (is_object($argument)) {
                $parsedArguments[] = get_class($argument) ?? ";;";
                continue;
            }

            if (is_array($argument)) {
                if (empty($argument) == 0 || !is_countable($argument)) {
                    $parsedArguments[] = "[]";
                    continue;
                }

                $parsedArguments[] = $this->parseArgument($argument);
                continue;
            }

            $parsedArguments[] = "null";
        }

        $parsedArguments = array_map(function ($value) {
            return is_array($value) ? "[" . join(", ", $value) . "]" : $value;
        }, $parsedArguments);

        return $parsedArguments;
    }

    /**
     * Convert method modifiers to string
     * 
     * @param ReflectionMethod $method
     * 
     * @return string
     */
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
     * Parse part of comments
     * 
     * @param string $comment
     * 
     * @return string[]
     */
    public static function parseComments(string $comment)
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

    /**
     * Trim comment
     * 
     * @param string $comment
     * 
     * @return string
     */
    public static function trimComment(string $comment)
    {
        $comment = trim($comment);
        $comment = rtrim($comment, "/");
        $comment = ltrim($comment, "*");
        $comment = trim($comment);

        return $comment;
    }

    /**
     * Convert argument to string
     * 
     * @param array $arguments
     * 
     * @return string
     */
    public function argumentToString(array $arguments)
    {
        $arguments = array_map(function ($value) {
            return is_array($value) ? join(", ", $value) : (empty($value) ? "null" : $value);
        }, $arguments);
        $arguments = join(", ", $arguments);

        return $arguments;
    }

}
