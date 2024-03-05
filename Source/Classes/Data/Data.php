<?php

namespace Clover\Classes;

use Clover\Classes\Data\StringObject as StringObject;
use Clover\Classes\Data\IntegerObject as IntegerObject;
use Clover\Classes\Data\BooleanObject as BooleanObject;
use Clover\Classes\Data\DoubleObject as DoubleObject;
use Clover\Classes\Data\ArrayObject as ArrayObject;
use Clover\Classes\Data\ResourceObject as ResourceObject;
use Clover\Classes\Data\NullObject as NullObject;
use Clover\Classes\Data\Identifier as Identifier;

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
