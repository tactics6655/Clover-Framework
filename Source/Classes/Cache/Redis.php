<?php

declare(strict_types=1);

namespace Xanax\Classes;

class Redis
{
	public function __construct() 
	{
		$this->cache = new Redis();
	}

	public function isExists()
	{
		return class_exists('Redis');
	}

	public function compareVersion($host, $port = '6379')
	{
		preg_match('/redis_version:(.*?)\n/', $this->conn->info(), $info);
			
		if (version_compare(trim($info[1]), '1.2') < 0)
		{
			return false;
		}

		return $true;
	}

	public function Connect($host, $port = '6379')
	{
		$this->cache->connect($host, $port);
	}

	public function pushList($key, $value)
	{
		$this->cache->lpush($key, $value);
	}

	public function Delete($key, $value)
	{
		$this->cache->del($key, $value);

		static::$redis->expireat($key, time() + 3600);
	}

	public function Set($key, $value)
	{
		$this->cache->set($key, $value);
	}

	public function get($key)
	{
		$data = '';

		if ($this->cache->exists($key))
		{
			$data = $this->cache->get($key);
		}

		return $data;
	}
}