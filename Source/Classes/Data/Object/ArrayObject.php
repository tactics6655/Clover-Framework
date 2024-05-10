<?php

namespace Clover\Classes\Data;

use Clover\Classes\ArraySummarizer;
use Clover\Classes\Data\BaseObject as BaseObject;
use Countable;
use Iterator;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Traversable;
use ReturnTypeWillChange;

#[\AllowDynamicProperties]
class ArrayObject extends BaseObject implements \ArrayAccess, Iterator, Countable
{

    protected $rawData;

    public function __construct($data = [])
    {
        $this->rawData = $data;
    }

    function fillRange($start, $end)
    {
        $numbers = new ArrayObject(range($start, $end));

        $this->mergeUnique($numbers);

        return $this;
    }

    function fillPrimes($start, $end)
    {
        $primes = [];

        for ($i = $start; $i <= $end; $i++) {
            if ($i == 2) {
                $primes[] = 2;
                continue;
            }

            $isPrime = true;
            $sqrt = sqrt($i);
            for ($j = 2; $j <= $sqrt; $j++) {
                if ($i % $j == 0) {
                    $isPrime = false;
                    break;
                }
            }

            if ($isPrime) {
                $primes[] = $i;
            }
        }

        $this->setRawData($primes);
    }

    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value): void
    {
        if ($offset instanceof StringObject) {
            $offset = $offset->__toString();
        }

        if ($offset === null) {
            $this->rawData[] = $value;
        } else {
            $this->rawData[$offset] = $value;
        }
    }

    #[ReturnTypeWillChange]
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

    #[ReturnTypeWillChange]
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

    #[ReturnTypeWillChange]
    public function offsetGet($offset): mixed
    {
        if ($offset instanceof StringObject) {
            $offset = $offset->__toString();
        }

        return $this->rawData[$offset] ?? null;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->rawData[$offset]);
    }

    public function addWithKey($key, $item)
    {
        if ($key instanceof StringObject) {
            $key = $key->__toString();
        }

        $this->rawData[$key] = $item;
    }

    public function getByIndex($index)
    {
        $this->rawData = $this->rawData[$index];

        return $this;
    }

    public function summary()
    {
        $arraySummarizer = new ArraySummarizer();
        $summaryData = $arraySummarizer->summarizeArray(array_unique($this->getRawData()));
        $this->setRawData($summaryData);

        return $this;
    }

    public function add($item)
    {
        $this->rawData[] = $item;
    }

    /**
     * Merge two arrays
     * 
     * @param ArrayObject $array
     * 
     * @return ArrayObject
     */
    public function merge($array): self
    {
        $this->rawData = array_merge($this->rawData, $array->rawData);

        return $this;
    }

    /**
     * Merge two arrays and removes duplicate values
     * 
     * @param ArrayObject $array
     * 
     * @return ArrayObject
     */
    public function mergeUnique($array): self
    {
        $this->rawData = array_unique($this->merge($array)->rawData);

        return $this;
    }

    public function mergeRecursive($array): self
    {
        $this->rawData = array_merge_recursive($this->getRawData(), $array);

        return $this;
    }

    public function mergeNoClobber($array): self
    {
        $this->rawData = array_merge_noclobber($this->getRawData(), $array);

        return $this;
    }

    public function mergeClobber($array): self
    {
        $this->rawData = array_merge_clobber($this->getRawData(), $array);

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

    public function toString(): StringObject
    {
        return new StringObject($this->rawData);
    }

    public function isContains($string)
    {
        return in_array($this->getRawData(), $string);
    }

    public function pop()
    {
        $data = $this->getRawData();
        $data = array_pop($data);

        $this->setRawData($data);

        return $this->toObject();
    }

    public function isContainKey($key)
    {
        return array_key_exists($key, $this->rawData);
    }

    public function get($key): bool|self|StringObject
    {
        if ($key instanceof StringObject) {
            $key = $key->__toString();
        }

        $data = $this->rawData[$key];

        if (is_string($data)) {
            return new StringObject($data);
        }

        if ($data instanceof ArrayObject) {
            return $data;
        }

        $this->setRawData($data);

        return $this;
    }

    /**
     * Return all the keys or a subset of the keys of an array
     * 
     * @return ArrayObject
     */
    public function getKeys(): self
    {
        $clone = array_keys($this->rawData);

        return new ArrayObject($clone);
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

    public function reverse()
    {

        $this->rawData = array_reverse($this->rawData);

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

    public function isCountable(): bool
    {
        return is_countable($this->getRawData());
    }

    /**
     * Checks if the given key or index exists in the array
     *
     * @param bool|float|int|resource|string|null $key
     *
     * @return bool
     */
    public function isKeyExists(mixed $key): bool
    {
        return array_key_exists($key, $this->rawData);
    }

    /**
     * Searches the array for a given value and returns the first corresponding key if successful
     *
     * @return bool|int|string
     */
    public function getKeyByValue(string $key): bool|int|string
    {
        return array_search($key, $this->rawData);
    }

    public function __toString()
    {
        if ($this->isCountable() && $this->count() > 0) {
            return implode(" ", $this->rawData);
        }

        return $this->rawData;
    }

    /**
     * Computes the difference of arrays
     *
     * @param ArrayObject $array
     *
     * @return ArrayObject
     */
    public function computesDifference($array): self
    {
        $cloneOriginal = &$this;
        $cloneTarget = &$array;

        $this->rawData = array_diff($cloneOriginal->rawData, $cloneTarget->rawData);

        return $this;
    }

    public function isEquals($b): bool
    {
        return
            count($this->rawData)                   == count($b->rawData) &&
            array_diff($this->rawData, $b->rawData) == array_diff($b->rawData, $this->rawData);
    }

    /**
     * Sort an array
     * 
     * @param int $flags
     *
     * @return ArrayObject
     */
    public function sort($flags = SORT_REGULAR): self
    {
        $clone = $this->rawData;

        sort($clone, $flags);

        return new ArrayObject($clone);
    }

    public function isList()
    {
        return array_is_list($this->getRawData());
    }

    public function getRandom($num = 1)
    {
        $data = $this->getRawData();
        $data = array_rand($data, $num);

        $this->setRawData($data);

        return $this->toObject();
    }

    /**
     * Join array elements with a string
     *
     * @param array|string $separator
     *
     * @return StringObject
     */
    public function join(array|string $separator): StringObject
    {
        $clone = implode($separator, $this->rawData);

        return new StringObject($clone);
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

    public function clear()
    {
        $this->rawData = [];

        return $this;
    }
}
