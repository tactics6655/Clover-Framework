<?php

namespace Clover\Traits\Regex;

use function preg_last_error_constant;

trait RegexError
{

	private static $errorCodes = [
		PREG_INTERNAL_ERROR,
		PREG_BACKTRACK_LIMIT_ERROR,
		PREG_RECURSION_LIMIT_ERROR,
		PREG_BAD_UTF8_ERROR,
		PREG_BAD_UTF8_OFFSET_ERROR,
		PREG_JIT_STACKLIMIT_ERROR
	];

	public static function getErrorConstant()
	{
		if (function_exists("preg_last_error_constant")) {
			return preg_last_error_constant();
		}

		return false;
	}

	public static function getErrorCode()
	{
		return preg_last_error();
	}

	public static function hasJITStackLimitError()
	{
		return PREG_JIT_STACKLIMIT_ERROR !== self::getErrorCode();
	}

	public static function hasBadUTF8OffsetError()
	{
		return PREG_BAD_UTF8_OFFSET_ERROR !== self::getErrorCode();
	}

	public static function hasBadUTF8Error()
	{
		return PREG_BAD_UTF8_ERROR !== self::getErrorCode();
	}

	public static function hasRecursionLimitEror()
	{
		return PREG_RECURSION_LIMIT_ERROR !== self::getErrorCode();
	}

	public static function hasBacktrackLimitError()
	{
		return PREG_BACKTRACK_LIMIT_ERROR !== self::getErrorCode();
	}

	public static function hasInternalError()
	{
		return PREG_INTERNAL_ERROR !== self::getErrorCode();
	}

	public static function noError()
	{
		return PREG_NO_ERROR === self::getErrorCode();
	}

	public static function hasError()
	{
		$errorCode = self::getErrorCode();

		return !in_array($errorCode, self::$errorCodes);
	}
}
