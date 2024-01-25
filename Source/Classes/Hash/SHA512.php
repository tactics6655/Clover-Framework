<?php

namespace Neko\Classes\Hash;

class SHA512
{
	public function encrypt($string, $useBase64 = true)
	{
		$hashed = hash('sha512', $string, true);

		return $useBase64 ? base64_encode($hashed) : $hashed;
	}
}
