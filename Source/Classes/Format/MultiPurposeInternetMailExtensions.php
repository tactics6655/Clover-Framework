<?php

declare(strict_types=1);

namespace Neko\Classes\Format;

class MultiPurposeInternetMailExtensions
{

	private static $extension = '';

	private static $types;

	public static function getFileContentType($filePath)
	{
		return mime_content_type($filePath);
	}

	public static function getContentTypeFromExtension($extension = '')
	{
		self::$types = include(dirname(__FILE__) . '/../../Defaults/MultiPurposeInternetMailExtensions.php');

		$type = '';

		if (!$extension && self::$extension) {
			$extension = self::$extension;
		}

		if (isset(self::$types[$extension])) {
			$type = self::$types[$extension]['type'];
		}

		return $type;
	}
}
