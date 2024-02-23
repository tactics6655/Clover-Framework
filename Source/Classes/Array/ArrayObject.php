<?php

namespace Neko\Classes;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Traversable;

class ArrayObject
{

	/**
	 * Return all the keys or a subset of the keys of an array
	 * 
	 * @param array $array
	 * 
	 * @return array
	 */
	public static function getKeys(array $array)
	{
		return array_keys($array);
	}

	/**
	 * Get the last key of the given array without affecting the internal array pointer
	 * 
	 * @param array $array
	 * 
	 * @return int|string|null
	 */
	public static function getLastKey(array $array)
	{
		return array_key_last($array);
	}

	/**
	 * Get the first key of the given array without affecting the internal array pointer.
	 * 
	 * @param array $array
	 * 
	 * @return int|string|null
	 */
	public static function getFirstKey(array $array)
	{
		return array_key_first($array);
	}

	/**
	 * Return all the values of an array
	 * 
	 * @param array $array
	 * 
	 * @return array
	 */
	public static function getAllValues(array $array)
	{
		return array_values($array);
	}

	/**
	 * Shuffle an array
	 * 
	 * @param array $array
	 * 
	 * @return bool
	 */
	public static function shuffle(array $array)
	{
		return shuffle($array);
	}

	/**
	 * Sort an array using a case insensitive "natural order" algorithm
	 * 
	 * @param array $array
	 * 
	 * @return bool
	 */
	public static function sortByCaseInsensitiveNaturalOrderAlgorithm(array $array)
	{
		return natcasesort($array);
	}

	/**
	 * Sort an array using a "natural order" algorithm
	 * 
	 * @param array $array
	 * 
	 * @return bool
	 */
	public static function sortByNaturalOrderAlgorithm(array $array)
	{
		return natsort($array);
	}

	/**
	 * Sort an array by key in descending order
	 * 
	 * @param array $array
	 * 
	 * @return bool
	 */
	public static function sortByKeyInDescendingOrder(array $array)
	{
		return krsort($array);
	}

	/**
	 * Sort an array by key in ascending order
	 * 
	 * @param array $array
	 * 
	 * @return bool
	 */
	public static function sortByKeyInAscendingOrder(array $array)
	{
		return ksort($array);
	}

	public static function setDeepCopy(&$original, $array, $value)
	{
		$current = &$original;
		foreach ($array as $key) {
			$current = &$current[$key];
		}

		$current = $value;
	}

	/**
	 * Fetch a key from an array
	 * 
	 * @param array $array
	 * 
	 * @return int|string|null
	 */
	public static function fetchKey(array $array)
	{
		return key($array);
	}

	/**
	 * Checks if the given key or index exists in the array
	 * 
	 * @param array $array
	 * 
	 * @return bool
	 */
	public static function isKeyExists(array $array, $key)
	{
		return array_key_exists($key, $array);
	}

	/**
	 * Searches the array for a given value and returns the first corresponding key if successful
	 * 
	 * @param array $array
	 * 
	 * @return bool|int|string
	 */
	public static function getKeyByValue(array $array, string $key)
	{
		return array_search($key, $array);
	}

	public static function isTraversable($array)
	{
		if ($array instanceof Traversable) {
			return true;
		}

		return false;
	}

	public static function getDepth(array $array)
	{
		$depth = 0;
		$arrayReclusive = new RecursiveArrayIterator($array);
		$iteratorReclusive = new RecursiveIteratorIterator($arrayReclusive);

		foreach ($iteratorReclusive as $iterator) {
			$currentDepth = $iterator->getDepth();

			$depth = $currentDepth > $depth ? $currentDepth : $depth;
		}

		return $depth;
	}
}
