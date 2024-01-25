<?php

use Neko\Enumeration\PHPINI;

class INIConfiguration
{

	public static function setDisplayStatupErrors(bool $displayErrors)
	{
		ini_set(PHPINI::DISPLAY_STARTUP_ERRORS, $displayErrors ? 'On' : 'Off');
	}

	public static function setDisplayErrors(string $displayErrors)
	{
		ini_set(PHPINI::DISPLAY_ERRORS, $displayErrors);
	}

	public static function isSessionUseCookies()
	{
		return ini_get(PHPINI::SESSION_USE_COOKIES);
	}

	public static function getMaxPostSize()
	{
		return ini_get(PHPINI::MAX_POST_DATA_SIZE);
	}

	public static function getMaxUploadFileSize()
	{
		return ini_get(PHPINI::MAX_UPLOAD_FILESIZE);
	}

	public static function isShortOpenTagAllowed()
	{
		return ini_get(PHPINI::ALLOW_SHORT_OPEN_TAG) == 1;
	}

	public static function isFileUploadAllowed()
	{
		return ini_get(PHPINI::ALLOW_FILE_UPLOADS) == 1;
	}

	public static function getMaxUploadFileNumber()
	{
		return ini_get(PHPINI::MAX_UPLOAD_FILE_NUMBER) == 1;
	}

	public static function getUploadTemporaryDirectory()
	{
		return ini_get(PHPINI::UPLOAD_TEMPORARY_DIRECTORY) == 1;
	}

	public static function getMemoryLimit()
	{
		return ini_get(PHPINI::MEMORY_LIMIT) == 1;
	}

	public static function getMaxMultipartBodyParts()
	{
		return ini_get(PHPINI::MAX_MULTIPART_BODY_PARTS) == 1;
	}
}
