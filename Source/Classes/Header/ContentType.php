<?php


declare(strict_types=1);

namespace Neko\Classes;

class ContentType extends Header
{

	public static function response($value)
	{
		parent::responseWithKey('Content-Type', $value);
	}

	public static function urlencodedForm()
	{
		self::response("application/x-www-form-urlencoded");
	}

	public static function gdImage()
	{
		self::response("image/gd");
	}

	public static function json()
	{
		self::response("application/json");
	}
}
