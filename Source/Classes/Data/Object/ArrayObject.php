<?php

namespace Neko\Classes\Data;

use Neko\Classes\Data\BaseObject as BaseObject;
use Neko\Classes\Data\IntegerObject as IntegerObject;
use Neko\Classes\Data\BooleanObject as BooleanObject;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Traversable;

#[\AllowDynamicProperties]
class ArrayObject extends BaseObject
{

    protected $raw_data;

    public function __construct($data = [])
    {
        $this->raw_data = $data;
    }

    public function addWithKey($key, $item)
    {
        $this->raw_data[$key] = $item;
    }

    public function add($item)
    {
        $this->raw_data[] = $item;
    }

    /**
     * Iteratively reduce the array to a single value using a callback function
     */
    public function reduce($method, $initial = null)
    {
        $data = array_reduce($this->raw_data, $method, $initial);

        if (isset($data)) {
            $this->raw_data = $data;
        }

        return $this;
    }

    /**
     * Iterates over each value in the array passing them to the callback function
     */
    public function filter($method)
    {
        $data = array_filter($this->raw_data, $method);

        if (isset($data)) {
            $this->raw_data = $data;
        }

        return $this;
    }

    /**
     * Applies the callback to the elements of the given arrays
     */
    public function map($method)
    {
        $data = array_map($method, $this->raw_data);

        if (isset($data)) {
            $this->raw_data = $data;
        }

        return $this;
    }

    public function get($key)
    {
        $data = $this->raw_data[$key];

        if (is_string($data)) {
            return new StringObject($data);
        }

        $this->raw_data = $data;

        return $this;
    }

    /**
     * Return all the keys or a subset of the keys of an array
     * 
     * @return ArrayObject
     */
    public function getKeys()
    {
        $this->raw_data = array_keys($this->raw_data);

        return $this;
    }

    /**
     * Return all the values of an array
     */
    public function getValues()
    {
        $this->raw_data = array_values($this->raw_data);

        return $this;
    }

    /**
     * Shuffle an array
     */
    public function shuffle()
    {
        $this->raw_data = shuffle($this->raw_data);

        return $this;
    }

    /**
     * Sort an array using a case insensitive "natural order" algorithm
     * 
     * @return ArrayObject
     */
    public function sortByCaseInsensitiveNaturalOrderAlgorithm()
    {
        natcasesort($this->raw_data);

        return $this;
    }

    /**
     * Computes the intersection of arrays with additional index check
     */
    public function computeIntersectionWithIndex(?array $array = [])
    {
        $this->raw_data = array_intersect_assoc($this->raw_data, $array);

        return $this;
    }

    /**
     * Computes the intersection of arrays
     */
    public function computeIntersection(?array $array = [])
    {
        $this->raw_data = array_intersect($this->raw_data, $array);

        return $this;
    }

    /**
     * Sort an array using a "natural order" algorithm
     * 
     * @return ArrayObject
     */
    public function sortByNaturalOrderAlgorithm()
    {
        natsort($this->raw_data);

        return $this;
    }

    /**
     * Sort an array by key in descending order
     * 
     * @return ArrayObject
     */
    public function sortByKeyInReverseOrder()
    {
        krsort($this->raw_data);

        return $this;
    }

    /**
     * Sort an array by key
     * 
     * @return ArrayObject
     */
    public function sortByKey()
    {
        ksort($this->raw_data);

        return $this;
    }

    /**
     * Get the last key of the given array without affecting the internal array pointer
     * 
     * @return int|string|null
     */
    public function getLastKey()
    {
        return array_key_last($this->raw_data);
    }

    /**
     * Get the first key of the given array without affecting the internal array pointer.
     * 
     * @return int|string|null
     */
    public function getFirstKey()
    {
        return array_key_first($this->raw_data);
    }

    /**
     * Fetch a key from an array
     * 
     * @return int|string|null
     */
    public function fetchKey()
    {
        return key($this->raw_data);
    }

    public function getMaxDepth()
    {
        $currentDepth = 0;
        $depth = 0;
        $arrayReclusive = new RecursiveArrayIterator($this->raw_data);
        $iteratorReclusive = new RecursiveIteratorIterator($arrayReclusive);

        /** @var RecursiveIteratorIterator[RecursiveArrayIterator] $iteratorReclusive */
        foreach ($iteratorReclusive as $iterator) {
            $currentDepth = $iterator->getDepth();

            $depth = $currentDepth > $depth ? $currentDepth : $depth;
        }

        $this->raw_data = $depth;

        return new IntegerObject($this->raw_data);
    }

    /**
     * Check that array is traversable
     * 
     * @return bool
     */
    public function isTraversable($array)
    {
        return $array instanceof Traversable;
    }

    /**
     * Checks if the given key or index exists in the array
     * 
     * @return bool
     */
    public function isKeyExists($key)
    {
        return array_key_exists($this->raw_data, $key);
    }

    /**
     * Searches the array for a given value and returns the first corresponding key if successful
     * 
     * @return bool|int|string
     */
    public function getKeyByValue(string $key)
    {
        return array_search($key, (array)($this->raw_data));
    }

    public function __toString()
    {
        return implode(" ", $this->raw_data);
    }

    /**
     * Sort an array
     */
    public function sort($flags = SORT_REGULAR)
    {
        sort($this->raw_data, $flags);

        return $this;
    }

    /**
     * Join array elements with a string
     */
    public function join($delimiter)
    {
        $this->raw_data = implode($delimiter, $this->raw_data);

        return new StringObject($this->raw_data);
    }

    /**
     * Counts all elements in an array
     */
    public function size()
    {
        return new IntegerObject(count($this->raw_data));
    }
}
