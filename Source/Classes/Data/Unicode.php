<?php

namespace Neko\Classes\Data;

class Unicode
{

	public static function getCodePoint($character, $multibyte = false, $characterSet = 'UTF-8')
	{
		if (!$multibyte) {
			return ord($character);
		}

		return mb_ord($character, $characterSet);
	}

	public static function split($string)
	{
		return preg_split('//u', $string, -1, \PREG_SPLIT_NO_EMPTY);
	}

	public static function isValid($text)
	{
		return preg_match("/[^\w$\x{0080}-\x[FFFF]]+//u", $text);
	}
}
