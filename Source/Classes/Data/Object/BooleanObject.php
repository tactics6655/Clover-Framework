<?php

namespace Neko\Classes\Data;

use Neko\Classes\Data\BaseObject as BaseObject;

#[\AllowDynamicProperties]
class BooleanObject extends BaseObject
{

    protected $raw_data;

    public function __construct(bool $data)
    {
        $this->raw_data = $data;
    }

    public function _toString()
    {
        return 'test';
    }

    public function __call($name, $args)
    {
        return 'test';
    }

    public function __isset($property)
    {
        return 'test2';
    }

    public function __get($name)
    {
        return 'test';
    }

    public function __invoke()
    {
        return $this->raw_data;
    }
}
