<?php

declare(strict_types=1);

namespace Neko\Classes;

class Header
{
	public static function response($data)
	{
		header($data);
	}

	public static function isSent()
	{
		return headers_sent();
	}

	public static function responseWithKeyAndArray(String $header, array $pair)
	{
		if (function_exists('create_function')) {
			array_walk($pair, \create_function('&$i,$k', '$i:" $k:$i;";'));
		} else {
			array_walk($pair, function (&$i, $k) {
				$i = $k . ":" . $i . ";";
			});
		}

		$responseData = implode("", $pair);

		self::response($responseData);
	}

	public static function responseWithArray(array $pair)
	{
		if (function_exists('create_function')) {
			array_walk($pair, \create_function('&$i,$k', '$i:" $k:$i;";'));
		} else {
			array_walk($pair, function (&$i, $k) {
				$i = $k . ":" . $i . ";";
			});
		}

		$responseData = implode("", $pair);

		self::response($responseData);
	}

	public static function responseWithKey($key, $value)
	{
		$responseData = "$key : $value";

		self::response($responseData);
	}

	public static function responseContentType($value)
	{
		self::responseWithKey('Content-Type', $value);
	}

	public static function responseContentTransferEncoding($value)
	{
		self::responseWithKey('Content-Transfer-Encoding', $value);
	}

	public static function responseContentDisposition($value)
	{
		self::responseWithKey('Content-Disposition', $value);
	}

	public static function responseXXSSProtection($value)
	{
		self::responseWithKey('X-XSS-Protection', $value);
	}

	public static function responseXContentTypeOption($value)
	{
		self::responseWithKey('X-Content-Type-Options', $value);
	}

	public static function responseP3P()
	{
		self::responseWithKey('P3P', 'CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	}

	public static function responseConnection($value)
	{
		self::responseWithKey('Connection', $value);
	}

	public static function responseContentEncoding($value)
	{
		self::responseWithKey('Content-Encoding', $value);
	}

	public static function responseContentLength($value)
	{
		self::responseWithKey('Content-Length', $value);
	}

	public static function responseRedirectLocation($value)
	{
		self::responseWithKey('Location', $value);
	}

	public static function responseStatus($responseCode = '200', $responseMessage = 'OK', $protocol = 'HTTP', $protocolVersion = '1.0')
	{
		$responseData = sprintf("%s/%s %s %s", $protocol, $protocolVersion, $responseCode, $responseMessage);

		self::response($responseData);
	}

	public static function responseStatusByCode($code)
	{
		$responseMessage = self::getStatusMessageByCode($code);

		//TODO throw

		self::responseStatus($code, $responseMessage);
	}

	public static function fileAttachment($fileName)
	{
		header("Content-Disposition: attachment; filename=$fileName");
	}

	public static function getStatusMessageByCode($code)
	{
		$stateMessage = '';

		switch ($code) {
			case "200":
				$stateMessage = 'OK';
				break;
			case "201":
				$stateMessage = 'Created';
				break;
			case "202":
				$stateMessage = 'Accepted';
				break;
			case "204":
				$stateMessage = 'No Content';
				break;
			case "300":
				$stateMessage = 'Multiple Choices';
				break;
			case "301":
				$stateMessage = 'Moved Permanently';
				break;
			case "302":
				$stateMessage = 'Moved Temporarily';
				break;
			case "304":
				$stateMessage = 'Not Modified';
				break;
			case "400":
				$stateMessage = 'Bad Request';
				break;
			case "401":
				$stateMessage = 'Unauthorized';
				break;
			case "403":
				$stateMessage = 'Forbidden';
				break;
			case "404":
				$stateMessage = 'Not Found';
				break;
			case "500":
				$stateMessage = 'Internal Server Error';
				break;
			case "501":
				$stateMessage = 'Not Implemented';
				break;
			case "502":
				$stateMessage = 'Bad Gateway';
				break;
			case "503":
				$stateMessage = 'Service Unavailable';
				break;
			default:
				break;
		}

		return $stateMessage;
	}

	/**
	 * Return the nesting level of the output buffering mechanism
	 */
	public static function getNestingLevelOfOutputBufferingMechanism()
	{
		return ob_get_level();
	}

	public static function responseXSSBlock()
	{
		self::responseXXSSProtection('mode=block');
	}

	public static function responseNoSniff()
	{
		self::responseXContentTypeOption('nosniff');
	}
}
