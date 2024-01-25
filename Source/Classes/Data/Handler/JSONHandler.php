<?php

namespace Neko\Classes\Data;

class JSONHandler
{

	public static function decode($string)
	{
		return json_decode($string);
	}

	public static function encode($string)
	{
		return json_encode($string, JSON_PRETTY_PRINT);
	}

	public static function isJSON($string)
	{
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
}
