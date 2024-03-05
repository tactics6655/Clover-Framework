<?php

namespace Clover\Classes\DependencyInjection;

class Container
{
    private array $container = [];

    public function set($identifier, $value)
    {
        $this->container[$identifier] = $value;
    }

    public function has($identifier)
    {
        return isset($this->container[$identifier]);
    }

    public function getByType($type)
    {
        foreach ($this->container as $container) {
            if (get_class($container) === $type) {
                return $container;
            }
        }

        return false;
    }

    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return false;
        }

        if (is_callable($this->container[$identifier])) {
            $this->container[$identifier] = $this->resolve($identifier);
        }

        return $this->container[$identifier];
    }

    public function resolve($identifier)
    {
        if (!is_callable($this->container[$identifier])) {
            return false;
        }

        return call_user_func($this->container[$identifier]);
    }
}
