<?php


declare(strict_types=1);

namespace Xanax\Classes;

class ContentType extends Header
{

	public static function response($value)
	{
		parent::responseWithKey('Content-Type', $value);
	}

	public function urlencodedForm()
	{
		self::response("application/x-www-form-urlencoded");
	}

	public function gdImage()
	{
		self::response("image/gd");
	}

	public function json()
	{
		self::response("application/json");
	}
}
