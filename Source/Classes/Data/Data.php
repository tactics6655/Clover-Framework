<?php

namespace Xanax\Classes;

use Xanax\Classes\Data\StringObject as StringObject;
use Xanax\Classes\Data\IntegerObject as IntegerObject;
use Xanax\Classes\Data\BooleanObject as BooleanObject;
use Xanax\Classes\Data\DoubleObject as DoubleObject;
use Xanax\Classes\Data\ArrayObject as ArrayObject;
use Xanax\Classes\Data\ResourceObject as ResourceObject;
use Xanax\Classes\Data\NullObject as NullObject;

use Xanax\Classes\Data\Identifier as Identifier;

class Data
{
	private static $data;

	public function __constructor($data)
	{
		self::$data = $data;
	}

	public function toObject()
	{
		if (getType(self::$data) == 'string')
		{
			return new StringObject(self::$data);
		}
		else if (getType(self::$data == 'integer'))
		{
			return new IntegerObject(self::$data);
		}
		else if (getType(self::$data == 'boolean'))
		{
			return new BooleanObject(self::$data);
		}
		else if (getType(self::$data == 'double'))
		{
			return new DoubleObject(self::$data);
		}
		else if (getType(self::$data == 'array'))
		{
			return new ArrayObject(self::$data);
		}
		else if (getType(self::$data == 'resource'))
		{
			return new ResourceObject(self::$data);
		}
		else if (getType(self::$data == 'NULL'))
		{
			return new NullObject(self::$data);
		}
	}

	public function typeCasting($type)
	{
		switch($type)
		{
			case "string":
				self::$data = (string)self::$data;
				break;
			case "integer":
				self::$data = (integer)self::$data;
				break;
			case "float":
				self::$data = (float)self::$data;
				break;
			case "boolean":
				self::$data = (boolean)self::$data;
				break;
			case "array":
				self::$data = (array)self::$data;
				break;
		}
	}

}
