<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\BaseObject as BaseObject;

#[\AllowDynamicProperties]
class BooleanObject extends BaseObject
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
