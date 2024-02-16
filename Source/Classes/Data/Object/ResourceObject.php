<?php

namespace Neko\Classes\Data;

#[\AllowDynamicProperties]
class ResourceObject
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return (string)$this->data;
    }
}
