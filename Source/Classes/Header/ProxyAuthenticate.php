<?php


declare(strict_types=1);

namespace Neko\Classes;

class ProxyAuthenticate extends Header
{

	public static function response($value)
	{
		parent::responseWithKey('Proxy-Authenticate', $value);
	}

	public static function basicRealm($value)
	{
		$key = "Basic realm";
		$data = "$key=\"$value\"";

		self::response($data);
	}
}
