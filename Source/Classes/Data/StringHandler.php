<?php

declare(strict_types=1);

namespace Xanax\Classes\Data;

use Xanax\Exception\FileHandler\MemoryAllocatedException;
use Xanax\Validation\PHPValidation;
use Xanax\Classes\OperationSystem;
use Xanax\Enumeration\Encoding;

class StringHandler
{
	/**
	 * Check that contains string.
	 *
	 * @param string $text
	 * @param string $search
	 *
	 * @return bool
	 */
	public static function contains($haystack, $needle)
	{
		$isGreaterThanRequiredVersion = PHPValidation::versionGreaterThanCurrent("8.0");

		if ($isGreaterThanRequiredVersion && function_exists("str_contains")) {
			return str_contains($haystack, $needle);
		}

		$position = strpos($haystack, $needle);

		return $position > -1;
	}

	public static function camelize($string)
	{
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
	}

	public static function pad($string, $length, $padString = " ", $type, $encoding = Encoding::UTF8)
	{
		if (function_exists("mb_str_pad")) {
			return mb_str_pad($string, $length, $padString, $type, $encoding);
		}

		return str_pad($string, $length, $padString, $type);
	}

	public static function padBoth($string, $length, $padString = " ")
	{
		return self::pad($string, $length, $padString, \STR_PAD_BOTH);
	}

	public static function padRight($string, $length, $padString = " ")
	{
		return self::pad($string, $length, $padString, \STR_PAD_RIGHT);
	}

	public static function padLeft($string, $length, $padString = " ")
	{
		return self::pad($string, $length, $padString, \STR_PAD_LEFT);
	}

	public static function endsWith($haystack, $needle)
	{
		$isGreaterThanRequiredVersion = PHPValidation::versionGreaterThanCurrent("8.0");

		if ($isGreaterThanRequiredVersion && function_exists("str_ends_with")) {
			return str_ends_with($haystack, $needle);
		}

		return self::indexOf($haystack, $needle) === (strlen($haystack) - strlen($needle));
	}

	public static function startsWith($haystack, $needle)
	{
		$isGreaterThanRequiredVersion = PHPValidation::versionGreaterThanCurrent("8.0");

		if ($isGreaterThanRequiredVersion && function_exists("str_contains")) {
			return str_starts_with($haystack, $needle);
		}

		return self::indexOf($haystack, $needle) === true;
	}

	public static function indexOf(string $haystack, string $needle, int $offset = 0, Encoding $encoding = Encoding::UTF_8)
	{
		if (function_exists('mb_strpos')) {
			return mb_strpos($haystack, $needle, $offset, '');
		}

		return strpos($haystack, $needle, $offset);
	}

	public static function substringMultibyte($string, $start, $length, $prefix = '...')
	{
		if (mb_strlen($string) > (int)$length) {
			return mb_substr($string, $start, (int)$length) . $prefix;
		} else {
			return mb_substr($string, $start, (int)$length);
		}
	}

	public static function indexBehindOf($text, $start, $searchString, $behindString)
	{
		$aheadIndex = strpos($text, $behindString);

		$findedIndex = strpos($text, $searchString);

		return ($findedIndex < $aheadIndex) ? -1 : $findedIndex;
	}

	public static function indexHeadOf($text, $start, $searchString, $behindString)
	{
		$aheadIndex = strpos($text, $behindString);

		$findedIndex = strpos($text, $searchString);

		return ($findedIndex > $aheadIndex) ? -1 : $findedIndex;
	}

	public static function removeByteOrderMark($text, $encoding = Encoding::UTF_8)
	{
		$byteOrderMark = "EFBBBF";
		$result = "";

		switch ($encoding) {
			case Encoding::UTF_8:
				$byteOrderMark = "EFBBBF";
				break;
			case Encoding::UTF_16_BIG_ENDIAN:
				$byteOrderMark = "FEFF";
				break;
			case Encoding::UTF_16_LITTLE_ENDIAN:
				$byteOrderMark = "FFFE";
				break;
			case Encoding::UTF_32_BIG_ENDIAN:
				$byteOrderMark = "0000FEFF";
				break;
			case Encoding::UTF_32_LITTLE_ENDIAN:
				$byteOrderMark = "FFFE0000";
				break;
			default:
				break;
		}

		$hexString = self::substring(self::binaryToHex($text), 0, 6);

		if ($hexString === $byteOrderMark) {
			$find = pack('H*', $byteOrderMark);
			$result = preg_replace("/^$find/", '', $text);
		}

		return $result;
	}

	public static function toUpperCase($text, Encoding $encoding = Encoding::UTF_8)
	{
		if (function_exists('mb_strupper')) {
			return mb_strtoupper($text, $encoding);
		}

		return strtoupper($text);
	}

	public static function toLowerCase($text)
	{
		return strtolower($text);
	}

	public static function removeNullByte($input)
	{
		$clean = str_replace("\x00", '', $input);
		$clean = str_replace("\0", '', $input);
		$clean = str_replace(chr(0), '', $input);

		return $clean;
	}

	public static function removeDot($text)
	{
		return preg_replace("#(.*)-(.*)-(.*).(\d)-(.*)#", "$1-$2-$3$4-$5", $text);
	}

	public static function substring($binaryText, $start, $length)
	{
		return substr($binaryText, $start, $length);
	}

	public static function binaryToHex($binaryText)
	{
		return bin2hex($binaryText);
	}

	public static function getMaxAllocationSize(string $string): int
	{
		$memory_limit = ini_get('memory_limit');

		if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
			if ($matches[2] == 'M') {
				$memory_limit = $matches[1] * 1024 * 1024;
			} else if ($matches[2] == 'K') {
				$memory_limit = $matches[1] * 1024;
			}
		}

		$maxAllocationSize = $memory_limit - 2097184;

		return (int)($maxAllocationSize / strlen($string));
	}

	public static function repeat(string $string, int $multiplier)
	{
		if (self::getMaxAllocationSize($string) > $multiplier) {
			// Memory allocated error
			throw new MemoryAllocatedException("Memory Allocated");
		}

		return str_repeat($string, $multiplier);
	}

	public static function getRandomString($length = 1)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return $randomString;
	}

	public static function removeUtf8Bom($string)
	{
		$source = preg_replace('/^\xEF\xBB\xBF/', '', $string);

		return $source;
	}

	public static function getMD5String($string, $length = 32)
	{
		return $string == '' ? '' : substr(md5($string), -$length);
	}

	public function entrip($name, $length = 10)
	{
		if (preg_match('/^(.+?)#(.+)$/', $name, $match)) {
			list(, $name, $pass) = $match;
			$salt = substr($pass . 'H.', 1, 2);
			$salt = preg_replace('/[^\.-z]/', '.', $salt);
			$salt = strtr($salt, ':;<=>?@[\\]^_`', 'ABCDEFGabcdef');
			$trip = crypt($pass, $salt);
			$trip = substr($trip, -$length);
			$name = $name . '◆' . $trip;
		} else {
			$name = str_replace('◆', '◇', $name);
		}
		return $name;
	}

	public static function getMd5Uniqid($length = 20, $prefix = '')
	{
		$id = md5(uniqid($prefix, true));
		$id = substr($id, -$length);

		return $id;
	}

	public static function length($data)
	{
		return \strlen($data);
	}

	public static function intergerToBytes(string $string)
	{
		$length = strlen($string);
		$result = '';

		for ($i = $length - 1; $i >= 0; $i--) {
			$result .= chr(floor($string / pow(256, $i)));
		}

		return $result;
	}

	public static function hexToBinary(string $string)
	{
		$length = strlen($string);
		$result = '';

		for ($i = 0; $i < $length; $i += 2) {
			$result .= chr(hexdec(substr($string, $i, 2)));
		}

		return $result;
	}

	public static function removeNullBytes(string $string)
	{
		$clean = str_replace("\x00", '', $string);
		$clean = str_replace("\0", '', $string);
		$clean = str_replace(chr(0), '', $string);

		return $clean;
	}

	public static function getRandomHex(int $length = 32)
	{
		$output = self::getRandomBytes($length);

		return bin2hex($output);
	}

	public static function isEmpty($string)
	{
		return empty($string);
	}

	public static function isNull($string)
	{
		return is_null($string);
	}

	public static function getRandomBytes(int $length = 32)
	{
		$bytes = min(32, $length);

		$isWindows = OperationSystem::isWindows();

		if (function_exists('random_bytes')) {
			try {
				$output = random_bytes($bytes);
			} catch (\Exception $e) {
				$output = false;
			}
		}

		if ($output === false) {
			if (function_exists('mcrypt_create_iv') && !$isWindows) {
				$output = mcrypt_create_iv($length, \MCRYPT_DEV_URANDOM);
			} else if (function_exists('openssl_random_pseudo_bytes') && !$isWindows) {
				$output = openssl_random_pseudo_bytes($length);
			} else if (function_exists('mcrypt_create_iv') && $isWindows) {
				$output = mcrypt_create_iv($bytes, \MCRYPT_RAND);
			}
		}

		return $output;
	}
}
