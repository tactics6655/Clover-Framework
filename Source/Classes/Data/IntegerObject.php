<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\BaseObject as BaseObject;

class IntegerObject extends BaseObject
{

    protected static $data;

    public function __construct($data) 
    {
        $this->data = $data;
    }

    public function __toString() 
    {
        return (string)($this->data);
    }

	public static function isNumeric($string)
	{
		return is_numeric($string);
	}

}