<?php

namespace Xanax\Classes\Data;

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

    public function Map($method)
    {
        $data = $method($this->raw_data);

        if (isset($data))
        {
            $this->raw_data = $data;
        }

        return $this;
    }

}