<?php

declare(strict_types=1);

namespace Xanax\Classes;

use function apc_clear_cache;

class Apc
{
	public function truncate()
	{
		return apc_clear_cache('user');
	}

	public function set($key, $validTime, $buffer)
	{
		return apc_store($key, array($_SERVER['REQUEST_TIME'], $buffer), $validTime);
	}

	public function delete($key)
	{
		return $key and apc_delete($key);
	}

	public function get($key, $limit)
	{
		$cache = apc_fetch($key, $limit);
		if ($limit > 0 && $limit > $cache[0]) {
			$this->delete($key);
		}

		return $cache[1];
	}
}
