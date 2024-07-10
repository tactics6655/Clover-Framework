<?php

declare(strict_types=1);

namespace Clover\Classes\Header;

use Clover\Classes\Header as Header;
use Clover\Enumeration\Encoding as Encoding;
use Clover\Classes\Format\MultiPurposeInternetMailExtensions as MIME;

class File extends Header
{

	public static function responseWithKeyAndArray(String $header, array $pair)
	{
		if (function_exists('create_function')) {
			array_walk($pair, \create_function('&$i,$k', '$i=" $k=$i;";'));
		} else {
			array_walk($pair, function (&$i, $k) {
				$i = $k . "=" . $i . ";";
			});
		}

		$responseData = implode("", $pair);

		parent::response($header . ";" . $responseData);
	}

	public static function responseWithCharset($application, $characterSet)
	{
		$characterSet = ['charset' => $characterSet];

		self::responseWithKeyAndArray('Content-Type:' . $application, $characterSet);
	}

	public static function responseWithOption($application, Encoding $characterSet)
	{
		if ($characterSet) {
			self::responseWithCharset($application, $characterSet->value);
		} else {
			self::response('application/zip; charset=UTF-8');
		}
	}

	public static function fromMIME($mime, $characterSet = Encoding::UTF_8)
	{
		self::responseWithOption(MIME::getContentTypeFromExtension($mime), $characterSet);
	}

	public static function fileZip($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("application/zip", $characterSet);
	}

	public static function filePlain($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("text/plain", $characterSet);
	}

	public static function fileXml($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("text/xml", $characterSet);
	}

	public static function fileJson($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("application/json", $characterSet);
	}

	public static function filePdf($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("application/pdf", $characterSet);
	}

	public static function fileGif($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("image/gif", $characterSet);
	}

	public static function fileJpeg($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("image/jpeg", $characterSet);
	}

	public static function fileJpg($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("image/jpg", $characterSet);
	}

	public static function filePng($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("image/png", $characterSet);
	}

	public static function fileJavascript($characterSet = Encoding::UTF_8)
	{
		self::responseWithOption("text/javascript", $characterSet);
	}
}
