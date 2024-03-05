<?php

namespace Clover\Classes\Data;

#[\AllowDynamicProperties]
class BaseObject
{

    protected $raw_data;

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
