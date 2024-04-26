<?php

namespace Clover\Classes\Data;

use Clover\Classes\Data\BaseObject as BaseObject;
use Clover\Classes\Data\IntegerObject as IntegerObject;
use Clover\Classes\Data\BooleanObject as BooleanObject;
use Countable;
use Iterator;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Traversable;

#[\AllowDynamicProperties]
class ArrayObject extends BaseObject implements \ArrayAccess, Iterator, Countable
{

    protected $rawData;

    public function __construct($data = [])
    {
        $this->rawData = $data;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->rawData[] = $value;
        } else {
            $this->rawData[$offset] = $value;
        }
    }

    public function current(): mixed
    {
        return current($this->rawData);
    }

    public function count(): int
    {
        return count($this->rawData);
    }

    public function valid(): bool
    {
        return key($this->rawData) !== null;
    }

    public function next(): void
    {
        next($this->rawData);
    }

    public function rewind(): void
    {
        reset($this->rawData);
    }

    public function key(): mixed
    {
        return key($this->rawData);
    }

    public function offsetUnset($offset): void
    {
        unset($this->rawData[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->rawData[$offset] ?? null;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->rawData[$offset]);
    }

    public function addWithKey($key, $item)
    {
        $this->rawData[$key] = $item;
    }

    public function add($item)
    {
        $this->rawData[] = $item;
    }

    /**
     * Merge two arrays
     * 
     * @return ArrayObject
     */
    public function merge($b): self
    {
        $this->rawData = array_merge($this->rawData, $b->rawData);

        return $this;
    }

    /**
     * Merge two arrays and removes duplicate values
     * 
     * @return ArrayObject
     */
    public function mergeUnique($b): self
    {
        $this->rawData = array_unique($this->merge($b)->rawData);

        return $this;
    }

    /**
     * Iteratively reduce the array to a single value using a callback function
     * 
     * @return ArrayObject
     */
    public function reduce($method, $initial = null): self
    {
        $data = array_reduce($this->rawData, $method, $initial);

        if (isset($data)) {
            $this->rawData = $data;
        }

        return $this;
    }

    /**
     * Iterates over each value in the array passing them to the callback function
     * 
     * @return ArrayObject
     */
    public function filter($method): self
    {
        $data = array_filter($this->rawData, $method);

        if (isset($data)) {
            $this->rawData = $data;
        }

        return $this;
    }

    /**
     * Applies the callback to the elements of the given arrays
     * 
     * @return ArrayObject
     */
    public function map($method): self
    {
        $data = array_map($method, $this->rawData);

        if (isset($data)) {
            $this->rawData = $data;
        }

        return $this;
    }

    public function get($key): self|StringObject
    {
        $data = $this->rawData[$key];

        if (is_string($data)) {
            return new StringObject($data);
        }

        $this->rawData = $data;

        return $this;
    }

    /**
     * Return all the keys or a subset of the keys of an array
     * 
     * @return ArrayObject
     */
    public function getKeys(): self
    {
        $this->rawData = array_keys($this->rawData);

        return $this;
    }

    /**
     * Return all the values of an array
     * 
     * @return ArrayObject
     */
    public function getValues(): self
    {
        $this->rawData = array_values($this->rawData);

        return $this;
    }

    /**
     * Shuffle an array
     * 
     * @return ArrayObject
     */
    public function shuffle(): self
    {
        $this->rawData = shuffle($this->rawData);

        return $this;
    }

    /**
     * Sort an array using a case insensitive "natural order" algorithm
     * 
     * @return ArrayObject
     */
    public function sortByCaseInsensitiveNaturalOrderAlgorithm(): self
    {
        natcasesort($this->rawData);

        return $this;
    }

    /**
     * Computes the intersection of arrays with additional index check
     * 
     * @return ArrayObject
     */
    public function computeIntersectionWithIndex(?array $array = []): self
    {
        $this->rawData = array_intersect_assoc($this->rawData, $array);

        return $this;
    }

    /**
     * Computes the intersection of arrays
     * 
     * @return ArrayObject
     */
    public function computeIntersection(?array $array = []): self
    {
        $this->rawData = array_intersect($this->rawData, $array);

        return $this;
    }

    /**
     * Sort an array using a "natural order" algorithm
     * 
     * @return ArrayObject
     */
    public function sortByNaturalOrderAlgorithm(): self
    {
        natsort($this->rawData);

        return $this;
    }

    /**
     * Sort an array by key in descending order
     * 
     * @return ArrayObject
     */
    public function sortByKeyInReverseOrder(): self
    {
        krsort($this->rawData);

        return $this;
    }

    /**
     * Sort an array by key
     * 
     * @return ArrayObject
     */
    public function sortByKey(): self
    {
        ksort($this->rawData);

        return $this;
    }

    /**
     * Get the last key of the given array without affecting the internal array pointer
     * 
     * @return int|string|null
     */
    public function getLastKey(): int|string|null
    {
        return array_key_last($this->rawData);
    }

    /**
     * Get the first key of the given array without affecting the internal array pointer.
     * 
     * @return int|string|null
     */
    public function getFirstKey(): int|string|null
    {
        return array_key_first($this->rawData);
    }

    /**
     * Fetch a key from an array
     * 
     * @return int|string|null
     */
    public function fetchKey(): int|string|null
    {
        return key($this->rawData);
    }

    /**
     * Get the maximum depth of array
     * 
     * @return int
     */
    public function getMaxDepth(): int
    {
        $currentDepth = 0;
        $depth = 0;
        $arrayReclusive = new RecursiveArrayIterator($this->rawData);
        $iteratorReclusive = new RecursiveIteratorIterator($arrayReclusive);

        /** @var RecursiveIteratorIterator[RecursiveArrayIterator] $iteratorReclusive */
        foreach ($iteratorReclusive as $iterator) {
            $currentDepth = $iterator->getDepth();

            $depth = $currentDepth > $depth ? $currentDepth : $depth;
        }

        return $depth;
    }

    /**
     * Check that array is traversable
     * 
     * @return bool
     */
    public function isTraversable(): bool
    {
        return $this->rawData instanceof Traversable;
    }

    /**
     * Checks if the given key or index exists in the array
     * 
     * @return bool
     */
    public function isKeyExists($key): bool
    {
        return array_key_exists($this->rawData, $key);
    }

    /**
     * Searches the array for a given value and returns the first corresponding key if successful
     * 
     * @return bool|int|string
     */
    public function getKeyByValue(string $key): bool|int|string
    {
        return array_search($key, (array)($this->rawData));
    }

    public function __toString()
    {
        return implode(" ", $this->rawData);
    }

    /**
     * Computes the difference of arrays
     * 
     * @return ArrayObject
     */
    public function computesDifference($array): self
    {
        $clone_original = &$this;
        $clone_target = &$array;

        $this->rawData = array_diff($clone_original->rawData, $clone_target->rawData);

        return $this;
    }

    public function isEquals($b): bool
    {
        return count($this->rawData) == count($b->rawData) &&
            array_diff($this->rawData, $b->rawData) == array_diff($b->rawData, $this->rawData);
    }

    /**
     * Sort an array
     * 
     * @return ArrayObject
     */
    public function sort($flags = SORT_REGULAR): self
    {
        sort($this->rawData, $flags);

        return $this;
    }

    /**
     * Join array elements with a string
     * 
     * @return StringObject
     */
    public function join($delimiter): StringObject
    {
        $this->rawData = implode($delimiter, $this->rawData);

        return new StringObject($this->rawData);
    }

    /**
     * Counts all elements in an array
     * 
     * @return int
     */
    public function size(): int
    {
        return count($this->rawData);
    }
}
