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
        self::$raw_data = $data;
    }

    public function __toString() 
    {
        return self::$raw_data;
    }

    public function Split($separator)
    {
        $array = explode($separator, self::$raw_data);

        return new ArrayObject($array);
    }

    public function Substring($start, $length) 
    {
        return StringHandler::Substring(self::$raw_data, $start, $length);
    }
    
    public function isEmpty() 
    {
        return StringHandler::isEmpty(self::$raw_data);
    }

    public function isNull() 
    {
        return StringHandler::isNull(self::$raw_data);
    }

    public function Contains($search) 
    {
        return StringHandler::Contains(self::$raw_data, $search);
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

    public function Length()
    {
        return StringHandler::Length(self::$raw_data);
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