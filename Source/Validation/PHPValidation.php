<?php

namespace Clover\Validation;

use Clover\Classes\OperationSystem;

class PHPValidation
{
	public static function getVersion()
	{
		return OperationSystem::getPHPVersion();
	}

	public static function versionGreaterThanCurrent($version)
	{
		$bool = version_compare(self::getVersion(), $version, '<') ? true : false;

		return $bool;
	}

	public static function versionCompare($version1, $version2)
	{
		$bool = version_compare($version1, $version2) >= 0 ? true : false;

		return $bool;
	}
}
