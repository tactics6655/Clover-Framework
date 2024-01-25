<?php

namespace Neko\Classes\Data;

#[\AllowDynamicProperties]
class BaseObject
{

    protected static $raw_data;

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function __toString()
    {
        return implode(" ", $this->raw_data);
    }

    public function reduce($method, $initial = null)
    {
        $data = array_reduce($this->raw_data, $method, $initial);

        if (isset($data)) {
            $this->raw_data = $data;
        }

        return $this;
    }

    public function filter($method)
    {
        $data = array_filter($this->raw_data, $method);

        if (isset($data)) {
            $this->raw_data = $data;
        }

        return $this;
    }

    public function map($method)
    {
        $data = array_map($method, $this->raw_data);

        if (isset($data)) {
            $this->raw_data = $data;
        }

        return $this;
    }

    public function length()
    {
        return 0;
    }
}
