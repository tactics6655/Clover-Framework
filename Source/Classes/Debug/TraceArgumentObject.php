<?php

declare(strict_types=1);

namespace Neko\Classes\Debug;

class TraceArgumentObject
{
    private $type;

    private $name;

    private $passedByReference = false;

    private $defaultValue;

    private array $arguments = [];

    public function __construct()
    {
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPassedByReference($passedByReference)
    {
        $this->passedByReference = $passedByReference;
    }

    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;
    }

    public function setArguments($arguments)
    {
        $this->arguments[] = $arguments;
    }

    public function getArgumentText()
    {
        $arguments = $this->getArguments();

        return array_map(function ($value) {
            return is_array($value) ? join(", ", $value) : $value;
        }, $arguments);
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function hasArguments()
    {
        return isset($this->arguments) && !empty($this->arguments);
    }
}