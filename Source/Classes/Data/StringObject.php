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
        self::$raw_data = $data;
    }

    public function __toString() 
    {
        return self::$raw_data;
    }

    public function split($separator)
    {
        $array = explode($separator, self::$raw_data);

        return new ArrayObject($array);
    }

    public function substring($start, $length) 
    {
        return StringHandler::substring(self::$raw_data, $start, $length);
    }
    
    public function isEmpty() 
    {
        return StringHandler::isEmpty(self::$raw_data);
    }

    public function isNull() 
    {
        return StringHandler::isNull(self::$raw_data);
    }

    public function contains($search) 
    {
        return StringHandler::contains(self::$raw_data, $search);
    }

    public function toUpperCase()
    {
        return StringHandler::toUpperCase(self::$raw_data);
    }

    public function removeByteOrderMark()
    {
        return StringHandler::removeByteOrderMark(self::$raw_data);
    }

    public function toLowerCase()
    {
        return StringHandler::toLowerCase(self::$raw_data);
    }

    public function length()
    {
        return StringHandler::length(self::$raw_data);
    }

    public function removeNullByte()
    {
        return StringHandler::removeNullByte(self::$raw_data);
    }

    public function removeUtf8Bom()
    {
        return StringHandler::removeUtf8Bom(self::$raw_data);
    }
    
}