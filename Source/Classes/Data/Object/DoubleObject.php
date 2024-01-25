<?php

namespace Neko\Classes\Data;

use Neko\Classes\Data\BaseObject as BaseObject;

#[\AllowDynamicProperties]

class DoubleObject extends BaseObject
{

    protected static $raw_data;

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function __toString()
    {
        return (string)$this->raw_data;
    }
}
