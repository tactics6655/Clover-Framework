<?php

namespace Neko\Validation;

use Neko\Validation\PHPValidation;

class FileValidation
{
	public static function isReadable($filename)
	{
		if (PHPValidation::versionGreaterThanCurrent('5.3.0')) {
			if (strlen($filename) >= PHP_MAXPATHLEN) {
				return false;
			}
		}

		return true;
	}

	public static function isNotReadable($filename)
	{
		return !self::isReadable($filename);
	}

	public static function isHTTPProtocol($filePath)
	{
		$regexr = '/^(http||https):\/\//i';

		if (preg_match($regexr, $filePath)) {
			return true;
		}

		return false;
	}

	public static function isNotHTTPProtocol($filePath)
	{
		return !self::isHTTPProtocol($filePath);
	}

	public static function isPharProtocol($filePath)
	{
		$regexr = '/^phar:\/\/.*/i';

		if (preg_match($regexr, $filePath)) {
			return true;
		}

		return false;
	}

	public static function isNotPharProtocol($filePath)
	{
		return !self::isPharProtocol($filePath);
	}

	public static function hasSubfolderSyntax($filePath)
	{
		$regexr = '/..\/$/i';

		if (preg_match($regexr, $filePath)) {
			return true;
		}

		return false;
	}

	public static function hasNotSubfolderSyntax($filePath)
	{
		return !self::hasSubfolderSyntax($filePath);
	}

	public static function hasExtention($filePath)
	{
		$regexr = '/^.*\.[A-Za-z0-9]{1,5}$/i';

		if (preg_match($regexr, $filePath)) {
			return true;
		}

		return false;
	}

	public static function hasNotExtention($filePath)
	{
		return !self::hasExtention($filePath);
	}
}
