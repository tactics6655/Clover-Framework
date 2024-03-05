<?php

declare(strict_types=1);

namespace Clover\Classes\Crypt;

use Clover\Classes\Crypt\OpenSSL;
use Exception;

class AES256CBC extends OpenSSLCipher
{
	const METHOD = 'AES-256-CBC';

	public static function encrypt($string, $key, $options = OPENSSL_RAW_DATA)
	{
		parent::setAlgorithm(self::METHOD);

		return parent::encrypt($string, $key, $options);
	}

	public static function decrypt($string, $key)
	{
		parent::setAlgorithm(self::METHOD);

		return parent::decrypt($string, $key);
	}
}
