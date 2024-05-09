<?php

namespace Clover\Classes\Data;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class BaseObject
{

    protected $rawData;

    public function __construct($data)
    {
        $this->rawData = $data;
    }

    public function setRawData($data)
    {
        $this->rawData = $data;
    }

    public function getRawData()
    {
        return $this->rawData;
    }

    public function __toString()
    {
        return implode(" ", $this->rawData);
    }

    public function typeOfString()
    {
        return $this instanceof StringObject;
    }

    public function typeCasting($type)
	{
        $data = $this->rawData;

		switch ($type) {
			case "string":
				$data = (string)$data;
				break;
			case "integer":
				$data = (int)$data;
				break;
			case "float":
				$data = (float)$data;
				break;
			case "boolean":
				$data = (bool)$data;
				break;
			case "array":
				$data = (array)$data;
				break;
            default:
                return false;
		}

        $this->setRawData($data);

        return true;
	}

	public function toObject()
	{
        $data = $this->getRawData();

		$type = getType($data);

		if ($type == 'string') {
			return new StringObject($data);
		} 
		
		if ($type == 'integer') {
			return new IntegerObject($data);
		}
		
		if ($type == 'array') {
			return new ArrayObject($data);
		}
		
		if ($type == 'double') {
			return new DoubleObject($data);
		}
		
		if ($type == 'array') {
			return new ArrayObject($data);
		}
		
		if ($type == 'resource') {
			return new ResourceObject($data);
		}
		
		if ($type == 'NULL') {
			return new NullObject($data);
		}
		
		return $data;
	}

    public function length()
    {
        return 0;
    }
}
