<?php

namespace Xanax\Framework\Component;

class Container
{
    private $container = [];

    public function Set($identifier, $value)
    {
        $this->container[$identifier] = $value;
    }

    public function Has($identifier)
    {
        return isset($this->container[$identifier]);
    }

    public function Get($identifier)
    {
        if (!$this->Has($identifier))
        {
            return false;
        }

        if (is_callable($this->container[$identifier]))
        {
            $this->container[$identifier] = $this->Resolve($identifier);
        }

        return $this->container[$identifier];
    }

    public function Resolve($identifier)
    {
        if (!is_callable($this->container[$identifier]))
        {
            return false;
        }

        return call_user_func($this->container[$identifier]);
    }

}