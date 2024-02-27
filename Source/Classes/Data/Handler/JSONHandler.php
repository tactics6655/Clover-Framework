<?php

namespace Neko\Classes\Data;

class JSONHandler
{

	public static function decode($string)
	{
		return json_decode($string);
	}

	public static function encode($string, ?int $flags = null)
	{
		if (null === $flags) {
            $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR;
        }

		return json_encode($string, $flags);
	}

	public static function isJSON($string)
	{
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
}
