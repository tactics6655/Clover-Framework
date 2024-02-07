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

    public function length()
    {
        return 0;
    }
}
