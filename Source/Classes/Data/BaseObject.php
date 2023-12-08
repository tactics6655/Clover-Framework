<?php

namespace Xanax\Classes\Data;

class BaseObject 
{

    protected static $data;

    public function __construct($data) 
    {
        $this->data = $data;
    }

    public function __toString() 
    {
        return implode(" ", $this->data);
    }

    public function Map($method)
    {
        $data = $method($this->data);

        if (isset($data))
        {
            $this->data = $data;
        }

        return $this;
    }

}