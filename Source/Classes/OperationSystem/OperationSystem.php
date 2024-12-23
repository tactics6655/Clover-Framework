<?php

declare(strict_types=1);

namespace Clover\Classes;

class OperationSystem
{

	/**
	 * Limits the maximum execution time
	 * 
	 * @param int $seconds
	 * 
	 * @return bool
	 */
	public static function setMaximumExecutionTimeLimit(int $seconds)
	{
		return set_time_limit($seconds);
	}

	public static function getLoadedINIFiles()
	{
		return php_ini_loaded_file();
	}

	public static function getScannedINIFiles()
	{
		return php_ini_scanned_files();
	}

	public static function clearLibxmlErrors()
	{
		libxml_clear_errors();
	}

	/**
	 * Disable libxml errors and allow user to fetch error information as needed
	 * 
	 * @return bool
	 */
	public static function disableLibXmlInternalErrors(?bool $use_errors = null): bool
	{
		return libxml_use_internal_errors($use_errors);
	}

	/**
	 * Find out whether an extension is loaded
	 * 
	 * @return bool
	 */
	public static function isExtensionLoaded($extension): bool
	{
		return extension_loaded($extension);
	}

	/**
	 * Sets the default timezone used by all date/time functions in a script
	 * 
	 * @return bool
	 */
	public static function setDefaultDateTimeZone($timeZoneId): bool
	{
		return date_default_timezone_set($timeZoneId);
	}

	public static function setDisplayStatupErrors(bool $displayErrors)
	{
		ini_set('display_startup_errors', $displayErrors ? 'On' : 'Off');
	}

	public static function setDisplayErrors(string $displayErrors)
	{
		ini_set('display_errors', $displayErrors);
	}

	public static function setErrorReportingLevel(int $level): int
	{
		return error_reporting($level);
	}

	public static function getPHPVersion()
	{
		return phpversion();
	}

	public static function isCommandLineInterface()
	{
		return (php_sapi_name() === 'cli');
	}

	public static function getBuiltOperationSystemString()
	{
		return PHP_OS;
	}

	public static function getIntergerSize()
	{
		return PHP_INT_SIZE;
	}

	public static function getMaximumIntergerSize()
	{
		return PHP_INT_MAX;
	}

	public static function is4BitOSBitOS()
	{
		if (PHP_INT_MAX == 0x7) // Maximum value of 4-bit sign integer
		{
			return true;
		}

		return false;
	}

	public static function is8BitOSBitOS()
	{
		if (PHP_INT_MAX == 0x7F) // Maximum value of 8-bit sign integer
		{
			return true;
		}

		return false;
	}

	public static function is16BitOS()
	{
		if (self::getIntergerSize() == 2 || self::getMaximumIntergerSize() == 0x7FFF) // Maximum value of 16-bit sign integer
		{
			return true;
		}

		return false;
	}

	public static function is32BitOS()
	{
		if (self::getIntergerSize() == 4 || self::getMaximumIntergerSize() == 0x7FFFFFFF) // Maximum value of 32-bit sign integer
		{
			return true;
		}

		return false;
	}

	public static function is64BitOS()
	{
		if (self::getIntergerSize() == 8 || self::getMaximumIntergerSize() == 0x7FFFFFFFFFFFFFFF) // Maximum value of 64-bit sign integer
		{
			return true;
		}

		return false;
	}

	public static function is128BitOS()
	{
		if (self::getMaximumIntergerSize() == 0x80000000000000000000000000000000) // Maximum value of 128-bit sign integer
		{
			return true;
		}

		return false;
	}

	public static function is256BitOS()
	{
		if (self::getMaximumIntergerSize() == 0x8000000000000000000000000000000000000000000000000000000000000000) // Maximum value of 256-bit sign integer
		{
			return true;
		}

		return false;
	}

	public static function isIIS()
	{
		return (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false);
	}

	public static function getHomePath()
	{
		return $_SERVER['HOME'] ?? "";
	}

	public static function getGatewayInterface()
	{
		return $_SERVER['GATEWAY_INTERFACE'];
	}

	public static function getMainServerSoftware()
	{
		return ucfirst(array_key_first(self::parseServerServerSoftware()));
	}

	public static function parseServerServerSoftware()
	{
		$software = self::getServerSoftware();

		$softwares = explode(' ', $software);

		return array_reduce($softwares, function ($res, $el) {
			list($software, $version) = array_merge(explode('/', $el), [1]);
			$res[$software] = (float) $version;
			return $res;
		}, []);
	}

	public static function getServerSoftware()
	{
		return $_SERVER['SERVER_SOFTWARE'] ?? "";
	}

	public static function getShortOperationSystemString()
	{
		return strtoupper(substr(self::getBuiltOperationSystemString(), 0, 3));
	}

	public static function isMachitosh()
	{
		return 'Darwin' === self::getFamily();
	}

	public static function isWindows()
	{
		return (self::getShortOperationSystemString() === 'WIN');
	}

	public static function isSessionUseCookies()
	{
		return ini_get('session.use_cookies');
	}

	public static function getMaxPostSize()
	{
		return ini_get('post_max_size');
	}

	public static function getMaxUploadFileSize()
	{
		return ini_get('upload_max_filesize');
	}

	public static function isShortOpenTagAllowed()
	{
		return ini_get('short_open_tag') == 1;
	}

	public static function isFileUploadAllowed()
	{
		return ini_get('file_uploads') == 1;
	}

	public static function getLoadAverage()
	{
		return sys_getloadavg();
	}

	public static function getUptime()
	{
		return system("uptime");
	}

	public static function getMemoryUsage()
	{
		return memory_get_usage();
	}

	public static function getFamily()
	{
		return PHP_OS_FAMILY;
	}

	public static function getMaximumPathLength()
	{
		return PHP_MAXPATHLEN;
	}

	public static function getCpuNumbers()
	{
		$cpu_numbers = 0;

		if (self::getFamily() == 'Windows') {
			$cpu_numbers = getenv("NUMBER_OF_PROCESSORS") + 0;
		} else {
			$cpu_numbers = substr_count(file_get_contents("/proc/cpuinfo"), "processor");
		}

		return (int) $cpu_numbers;
	}
}
