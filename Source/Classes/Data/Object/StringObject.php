<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\StringHandler as StringHandler;
use Xanax\Classes\Data\ArrayObject as ArrayObject;
use Xanax\Classes\Data\BaseObject as BaseObject;

#[\AllowDynamicProperties]
class StringObject extends BaseObject
{

    protected static $raw_data = '';

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function __toString()
    {
        return $this->raw_data;
    }

    public function trim(string $characters = " \n\r\t\v\0")
    {
        $this->raw_data = trim($this->raw_data, $characters);

        return $this;
    }

    public function split($separator)
    {
        $array = explode($separator, $this->raw_data);

        return new ArrayObject($array);
    }

    public function substring($start, $length)
    {
        $this->raw_data = StringHandler::substring($this->raw_data, $start, $length);

        return $this;
    }

    public function isEmpty()
    {
        $boolean = StringHandler::isEmpty($this->raw_data);

        return $boolean;
    }

    public function isNull()
    {
        $this->raw_data = StringHandler::isNull($this->raw_data);

        return $this;
    }

    public function contains($search)
    {
        $this->raw_data = StringHandler::contains($this->raw_data, $search);

        return $this;
    }

    public function toUpperCase()
    {
        $this->raw_data = StringHandler::toUpperCase($this->raw_data);

        return $this;
    }

    public function removeByteOrderMark()
    {
        $this->raw_data = StringHandler::removeByteOrderMark($this->raw_data);

        return $this;
    }

    public function toLowerCase()
    {
        $this->raw_data = StringHandler::toLowerCase($this->raw_data);

        return $this;
    }

    public function length()
    {
        $this->raw_data = StringHandler::length($this->raw_data);

        return $this;
    }

    public function removeNullByte()
    {
        $this->raw_data = StringHandler::removeNullByte($this->raw_data);

        return $this;
    }

    public function removeUtf8Bom()
    {
        $this->raw_data = StringHandler::removeUtf8Bom($this->raw_data);

        return $this;
    }
}
