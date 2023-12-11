<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\StringHandler as StringHandler;
use Xanax\Classes\Data\ArrayObject as ArrayObject;

use Xanax\Classes\Data\BaseObject as BaseObject;

class StringObject extends BaseObject
{

    protected static $raw_data;

    public function __construct($data) 
    {
        $this->raw_data = $data;
    }

    public function __toString() 
    {
        return $this->raw_data;
    }

    public function Split($separator)
    {
        $array = explode($separator, $this->raw_data);

        return new ArrayObject($array);
    }

    public function Substring($start, $length) 
    {
        return StringHandler::Substring($this->raw_data, $start, $length);
    }
    
    public function isEmpty() 
    {
        return StringHandler::isEmpty($this->raw_data);
    }

    public function isNull() 
    {
        return StringHandler::isNull($this->raw_data);
    }

    public function Contains($search) 
    {
        return StringHandler::Contains($this->raw_data, $search);
    }

    public function toUpperCase()
    {
        return StringHandler::toUpperCase($this->raw_data);
    }

    public function removeByteOrderMark()
    {
        return StringHandler::removeByteOrderMark($this->raw_data);
    }

    public function toLowerCase()
    {
        return StringHandler::toLowerCase($this->raw_data);
    }

    public function Length()
    {
        return StringHandler::Length($this->raw_data);
    }

    public function removeNullByte()
    {
        return StringHandler::removeNullByte($this->raw_data);
    }

    public function removeUtf8Bom()
    {
        return StringHandler::removeUtf8Bom($this->raw_data);
    }
    
}