<?php

namespace Clover\Classes\Data;

use Clover\Classes\Data\BaseObject as BaseObject;

#[\AllowDynamicProperties]
class NullObject extends BaseObject
{

    protected $raw_data;

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function __toString()
    {
        return (string)$this->raw_data;
    }
}
