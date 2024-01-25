<?php

namespace Neko\Classes;

use Neko\Classes\Data\StringObject as StringObject;
use Neko\Classes\Data\IntegerObject as IntegerObject;
use Neko\Classes\Data\BooleanObject as BooleanObject;
use Neko\Classes\Data\DoubleObject as DoubleObject;
use Neko\Classes\Data\ArrayObject as ArrayObject;
use Neko\Classes\Data\ResourceObject as ResourceObject;
use Neko\Classes\Data\NullObject as NullObject;
use Neko\Classes\Data\Identifier as Identifier;

class Data
{
	protected static $data;

	public function __construct($data)
	{
		self::$data = $data;
	}

	public function toObject()
	{
		if (getType(self::$data) == 'string') {
			return new StringObject(self::$data);
		} else if (getType(self::$data == 'integer')) {
			return new IntegerObject(self::$data);
		} else if (getType(self::$data == 'boolean')) {
			return new BooleanObject(self::$data);
		} else if (getType(self::$data == 'double')) {
			return new DoubleObject(self::$data);
		} else if (getType(self::$data == 'array')) {
			return new ArrayObject(self::$data);
		} else if (getType(self::$data == 'resource')) {
			return new ResourceObject(self::$data);
		} else if (getType(self::$data == 'NULL')) {
			return new NullObject(self::$data);
		} else {
			return self::$data;
		}
	}

	public function typeCasting($type)
	{
		switch ($type) {
			case "string":
				self::$data = (string)self::$data;
				break;
			case "integer":
				self::$data = (int)self::$data;
				break;
			case "float":
				self::$data = (float)self::$data;
				break;
			case "boolean":
				self::$data = (bool)self::$data;
				break;
			case "array":
				self::$data = (array)self::$data;
				break;
		}
	}
}
