<?php

declare(strict_types=1);

namespace Neko\Classes;

use function apc_clear_cache;

class Apc
{

	public function isExists(mixed $keys)
	{
		return \apc_exists($keys);
	}

	public function delete($key)
	{
		return \apc_delete($key);
	}

	public function compile($filename, $atomic = false)
	{
		return \apc_compile_file($filename, $atomic)
	}

	public function clear()
	{
		return \apc_clear_cache('user');
	}

	public function set($key, $validTime, $buffer)
	{
		return \apc_store($key, array($_SERVER['REQUEST_TIME'], $buffer), $validTime);
	}

	public function add($key, $value, $ttl)
	{
		\apc_add($key, $value, $ttl);
	}

	public function adds($values, $ttl)
	{
		\apc_add($values, null, $ttl);
	}

	public function delete($key)
	{
		return $key and \apc_delete($key);
	}

	public function get($key, $limit)
	{
		$cache = \apc_fetch($key, $limit);

		if ($limit > 0 && $limit > $cache[0]) {
			$this->delete($key);
		}

		return $cache[1];
	}
}
