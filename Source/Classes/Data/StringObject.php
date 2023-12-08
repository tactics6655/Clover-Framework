<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\StringHandler as StringHandler;
use Xanax\Classes\Data\ArrayObject as ArrayObject;

use Xanax\Classes\Data\BaseObject as BaseObject;

class StringObject extends BaseObject
{

    protected static $data;

    public function __construct($data) 
    {
        $this->data = $data;
    }

    public function __toString() 
    {
        return $this->data;
    }

    public function Split($separator)
    {
        $array = explode($separator, $this->data);

        return new ArrayObject($array);
    }

    public function Substring($start, $length) 
    {
        return StringHandler::Substring($this->data, $start, $length);
    }
    
    public function isEmpty() 
    {
        return StringHandler::isEmpty($this->data);
    }

    public function isNull() 
    {
        return StringHandler::isNull($this->data);
    }

    public function Contains($search) 
    {
        return StringHandler::Contains($this->data, $search);
    }

    public function toUpperCase()
    {
        return StringHandler::toUpperCase($this->data);
    }

    public function removeByteOrderMark()
    {
        return StringHandler::removeByteOrderMark($this->data);
    }

    public function toLowerCase()
    {
        return StringHandler::toLowerCase($this->data);
    }

    public function Length()
    {
        return StringHandler::Length($this->data);
    }

    public function removeNullByte()
    {
        return StringHandler::removeNullByte($this->data);
    }

    public function removeUtf8Bom()
    {
        return StringHandler::removeUtf8Bom($this->data);
    }
    
}