<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\BaseObject as BaseObject;
use Xanax\Classes\Data\IntegerObject as IntegerObject;
use Xanax\Classes\Data\BooleanObject as BooleanObject;

#[\AllowDynamicProperties]
class ArrayObject extends BaseObject
{

    protected static $raw_data;

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function get($key)
    {
        $data = $this->raw_data[$key];

        return new StringObject($data);
    }

    public function getKeys()
    {
        $this->raw_data = array_keys($this->raw_data);

        return $this;
    }

    public function getValues()
    {
        $this->raw_data = array_values($this->raw_data);

        return $this;
    }

    public function shuffle()
    {
        $this->raw_data = shuffle($this->raw_data);

        return $this;
    }

    public function sortByCaseInsensitiveNaturalOrderAlgorithm()
    {
        $this->raw_data = natcasesort($this->raw_data);

        return $this;
    }

    public function sortByNaturalOrderAlgorithm()
    {
        $this->raw_data = natsort($this->raw_data);

        return $this;
    }

    public function sortByKeyInReverseOrder()
    {
        $this->raw_data = krsort($this->raw_data);

        return $this;
    }

    public function sortByKey()
    {
        $this->raw_data = ksort($this->raw_data);

        return $this;
    }

    public function getDepth()
    {
        $currentDepth = 0;
        $depth = 0;
        $arrayReclusive = new \RecursiveArrayIterator($this->raw_data);
        $iteratorReclusive = new \RecursiveIteratorIterator($arrayReclusive);

        foreach ($iteratorReclusive as $iterator) {
            $currentDepth = $iteratorReclusive->getDepth();

            $depth = $currentDepth > $depth ? $currentDepth : $depth;
        }

        $this->raw_data = $depth;

        return new IntegerObject($this->raw_data);
    }

    public function isTraversable($array)
    {
        $this->raw_data = $array instanceof \Traversable;

        return new BooleanObject($this->raw_data);
    }

    public function isKeyExists($key)
    {
        $this->raw_data = array_key_exists($this->raw_data, $key);

        return new BooleanObject($this->raw_data);
    }

    public function __toString()
    {
        return implode(" ", $this->raw_data);
    }

    public function sort($flags = SORT_REGULAR)
    {
        sort($this->raw_data, $flags);

        return $this;
    }

    public function join($delimiter)
    {
        $this->raw_data = implode($delimiter, $this->raw_data);

        return new StringObject($this->raw_data);
    }

    public function length()
    {
        return new IntegerObject(count($this->raw_data));
    }
}
