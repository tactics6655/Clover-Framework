<?php

namespace Xanax\Classes;

use RecursiveArrayIterator;

class ArrayObject
{

	public static function getKeys(array $array)
	{
		return array_keys($array);
	}

	public static function getLastKey(array $array)
	{
		return array_key_last($array);
	}

	public static function getFirstKey(array $array)
	{
		return array_key_first($array);
	}

	public static function getAllValues(array $array)
	{
		return array_values($array);
	}

	public static function shuffle(array $array)
	{
		return shuffle($array);
	}

	public static function sortByCaseInsensitiveNaturalOrderAlgorithm(array $array)
	{
		return natcasesort($array);
	}

	public static function sortByNaturalOrderAlgorithm(array $array)
	{
		return natsort($array);
	}

	public static function sortByKeyInReverseOrder(array $array)
	{
		return krsort($array);
	}

	public static function sortByKey(array $array)
	{
		return ksort($array);
	}

	public static function setDeep(&$original, $array, $value)
	{
		$current = &$original;
		foreach ($array as $key) {
			$current = &$current[$key];
		}

		$current = $value;
	}

	public static function fetchKey(array $array)
	{
		return key($array);
	}

	public static function isKeyExists(array $array, $key)
	{
		return array_key_exists($array, $key);
	}

	public static function getKeyByValue(array $array, string $key)
	{
		return array_search($key, $array);
	}

	public static function isTraversable($array)
	{
		if ($array instanceof \Traversable) {
			return true;
		}

		return false;
	}

	public static function getDepth(array $array)
	{
		$depth = 0;
		$arrayReclusive = new \RecursiveArrayIterator($array);
		$iteratorReclusive = new \RecursiveIteratorIterator($arrayReclusive);

		foreach ($iteratorReclusive as $iterator) {
			$currentDepth = $iterator->getDepth();

			$depth = $currentDepth > $depth ? $currentDepth : $depth;
		}

		return $depth;
	}
}
