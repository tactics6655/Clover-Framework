<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\BaseObject as BaseObject;

class DoubleObject extends BaseObject
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