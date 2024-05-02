<?php

namespace Clover\Classes\Data;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class BaseObject
{

    protected $rawData;

    public function __construct($data)
    {
        $this->rawData = $data;
    }

    public function setRawData($data)
    {
        $this->rawData = $data;
    }

    public function getRawData()
    {
        return $this->rawData;
    }

    public function __toString()
    {
        return implode(" ", $this->rawData);
    }

    public function typeOfString()
    {
        return $this instanceof StringObject;
    }

    public function length()
    {
        return 0;
    }
}
