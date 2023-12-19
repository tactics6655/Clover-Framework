<?php

namespace Xanax\Framework\Component;

class Container
{
    private $container = [];

    public function set($identifier, $value)
    {
        $this->container[$identifier] = $value;
    }

    public function has($identifier)
    {
        return isset($this->container[$identifier]);
    }

    public function get($identifier)
    {
        if (!$this->has($identifier))
        {
            return false;
        }

        if (is_callable($this->container[$identifier]))
        {
            $this->container[$identifier] = $this->resolve($identifier);
        }

        return $this->container[$identifier];
    }

    public function resolve($identifier)
    {
        if (!is_callable($this->container[$identifier]))
        {
            return false;
        }

        return call_user_func($this->container[$identifier]);
    }

}