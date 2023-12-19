<?php

namespace Xanax\Classes\Data;

#[\AllowDynamicProperties]
class ResourceObject
{

    protected static \resource $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->data;
    }
}
