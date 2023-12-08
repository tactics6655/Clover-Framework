<?php

namespace Xanax\Classes\Data;

class ResourceObject 
{

    protected static $data;

    public function __construct($data) 
    {
        $this->data = $data;
    }

    public function __toString() 
    {
        return $this->data;
    }

}