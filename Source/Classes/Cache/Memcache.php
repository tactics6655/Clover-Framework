<?php

declare(strict_types=1);

namespace Neko\Classes;

use Memcached;
use Memcache;

class Memcache
{
	protected $type;
	protected Memcached | Memcache $cache;

	public function __construct()
	{
		if ($this->isMemcachedClassExists()) {
			$this->cache = new \Memcached;
			$this->type = "memcached";
		} else if ($this->isMemcacheClassExists()) {
			$this->cache = new \Memcache;
			$this->type = "memcache";
		}
	}

	public function isMemcacheClassExists()
	{
		return class_exists('Memcache');
	}

	public function isMemcachedClassExists()
	{
		return class_exists('Memcached');
	}

	public function connect($host, $port)
	{
		$this->cache->addServer($host, $port);
	}

	public function truncate()
	{
		return $this->cache->flush();
	}

	public function set($key, $validTime, $buffer)
	{
		if ($this->type == "memcached") {
			return $this->set($key, array(time(), $buffer), $validTime);
		} else if ($this->type == "memcache") {
			return $this->set($key, array(time(), $buffer), \MEMCACHE_COMPRESSED, $validTime);
		}
	}

	public function decrement($key, $amount)
	{
		return $this->cache->decrement($key, $amount);
	}

	public function increment($key, $amount)
	{
		return $this->cache->increment($key, $amount);
	}

	public function isExists($key)
	{
		return $this->get($key) !== false;
	}

	public function delete($key)
	{
		return $this->cache->delete($key);
	}

	public function get($key, $limit = 0)
	{
		$cache = $this->cache->get($key);

		if ($limit > 0 && $limit > $cache[0]) {
			$this->delete($key);
		}

		return $cache[1];
	}
}
