<?php

namespace Clover\Classes;

use Clover\Classes\Regex\StringResult as StringResult;
use Clover\Classes\Regex\ArrayResult as ArrayResult;
use Clover\Classes\Regex\Executor as Executor;
use Clover\Traits\Regex\RegexError;

class Regex
{
	use RegexError;

	public static function isValid($regex)
	{
		try {
			$oldLevel = error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
			error_reporting(E_ERROR | E_PARSE);
			preg_match($regex, "");
			error_reporting($oldLevel);
		} catch (\Exception $ignore) {
		}

		return self::hasError();
	}

	public static function filter(string $pattern, string $replacement, $subject)
	{
	}

	public static function quote(string $string, string $delimiter = NULL)
	{
	}

	public static function match(string $pattern, string $subject): ArrayResult
	{
		$result = Executor::match($pattern, $subject);

		return (new ArrayResult())->getSingleton($result);
	}

	public static function matchAll(string $pattern, string $subject): ArrayResult
	{
		$result = Executor::matchAll($pattern, $subject);

		return (new ArrayResult())->getSingleton($result);
	}

	public static function split(string $pattern, $subject, int $limit = -1)
	{
	}

	public static function replace(string $pattern, string $replacement, $subject, int $limit = -1)
	{
	}
}
