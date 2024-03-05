<?php

declare(strict_types=1);

namespace Clover\Classes\Crypt;

use Exception;

class OpenSSLCipher
{
	private static $algorithm = '';

	protected static function setAlgorithm($algorithm)
	{
		self::$algorithm = $algorithm;
	}

	public static function encrypt($string, $key, $options = OPENSSL_RAW_DATA)
	{
		if (mb_strlen($key, '8bit') !== 32) {
			throw new Exception('Needs a 256-bit key!');
		}

		$ivsize = OpenSSL::getCipherInitializationVectorLength(self::$algorithm);
		$iv     = OpenSSL::generatePseudoRandomStringOfBytes($ivsize);

		$ciphertext = OpenSSL::encrypt(
			$string,
			self::$algorithm,
			$key,
			$options,
			$iv
		);

		return base64_encode($iv . $ciphertext);
	}

	public static function decrypt($string, $key)
	{
		$string = base64_decode($string);

		if (mb_strlen($key, '8bit') !== 32) {
			throw new Exception('Needs a 256-bit key!');
		}

		$ivsize     = OpenSSL::getCipherInitializationVectorLength(self::$algorithm);
		$iv         = mb_substr($string, 0, $ivsize, '8bit');
		$ciphertext = mb_substr($string, $ivsize, null, '8bit');

		return OpenSSL::decrypt(
			$ciphertext,
			self::$algorithm,
			$key,
			OPENSSL_RAW_DATA,
			$iv
		);
	}
}
