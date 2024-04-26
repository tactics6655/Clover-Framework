<?php

namespace Clover\Classes\Data;

use Clover\Classes\Data\BaseObject as BaseObject;

#[\AllowDynamicProperties]
class NullObject extends BaseObject
{

    protected $rawData;

    public function __construct($data)
    {
        $this->rawData = $data;
    }

    public function __toString()
    {
        return (string)$this->rawData;
    }
}
