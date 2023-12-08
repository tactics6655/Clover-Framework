<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\BaseObject as BaseObject;
use Xanax\Classes\Data\IntegerObject as IntegerObject;
use Xanax\Classes\Data\BoolObject as BoolObject;

class ArrayObject extends BaseObject
{

    protected static $data;

    public function __construct($data) 
    {
        $this->data = $data;
    }

	public function getKeys() 
	{
		$this->data = array_keys($this->data);

        return $this;
	}

	public function getValues() 
	{
		$this->data = array_values($this->data);

        return $this;
	}

	public function Shuffle() 
	{
		$this->data = shuffle($this->data);

        return $this;
	}

	public function sortByCaseInsensitiveNaturalOrderAlgorithm() 
	{
		$this->data = natcasesort($this->data);

        return $this;
	}

	public function sortByNaturalOrderAlgorithm() 
	{
		$this->data = natsort($this->data);

        return $this;
	}

	public function sortByKeyInReverseOrder() 
	{
		$this->data = krsort($this->data);

        return $this;
	}

	public function sortByKey() 
	{
		$this->data = ksort($this->data);

        return $this;
	}

    public function getDepth()
    {
        $currentDepth = 0;
        $depth = 0;
		$arrayReclusive = new \RecursiveArrayIterator($this->data);
		$iteratorReclusive = new \RecursiveIteratorIterator($arrayReclusive);

		foreach ($iteratorReclusive as $iterator) 
		{
            $currentDepth = $iteratorReclusive->getDepth();

            $depth = $currentDepth > $depth ? $currentDepth : $depth;
		}

        $this->data = $depth;

        return new IntegerObject($this->data);
    }

	public function isTraversable() 
	{
		$this->data = $array instanceof \Traversable;

        return new BoolObject($this->data);
	}

	public function isKeyExists() 
	{
		$this->data = array_key_exists($this->data, $key);

        return new BoolObject($this->data);
	}

    public function __toString() 
    {
        return implode(" ", $this->data);
    }

    public function Sort($flags = SORT_REGULAR)
    {
        sort($this->data, $flags);

        return $this;
    }

    public function Join($delimiter)
    {
        $this->data = implode($delimiter, $this->data);

        return new StringObject($this->data);
    }

    public function Length()
    {
        return new IntegerObject(count($this->data));
    }

}