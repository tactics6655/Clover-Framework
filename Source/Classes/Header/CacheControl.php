<?php


declare(strict_types=1);

namespace Neko\Classes;

class CacheControl extends Header
{

	public static function response($value)
	{
		parent::responseWithKey('Cache-Control', $value);
	}

	public function minFresh($value)
	{
		$key = "min-fresh";
		$data = "$key=$value";

		self::response($data);
	}

	public function maxStale($value)
	{
		$key = "max-stale";
		$data = "$key=[=$value]";

		self::response($data);
	}

	public function maxAge($value)
	{
		$key = "max-age";
		$data = "$key=$value";

		self::response($data);
	}

	public function onlyIfCached()
	{
		self::response('only-if-cached');
	}

	public function noStore()
	{
		self::response('no-store');
	}

	public function noTransform()
	{
		self::response('no-transform');
	}

	public function noCache()
	{
		self::response('no-cache');
	}
}
