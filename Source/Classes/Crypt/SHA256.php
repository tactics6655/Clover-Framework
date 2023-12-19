<?php

declare(strict_types=1);

namespace Xanax\Classes;

class SHA256
{
	public function encrypt($string, $useBase64 = true)
	{
		$hashed = base64_encode(hash('sha256', $string, true));

		return $useBase64 ? base64_encode($hashed) : $hashed;
	}
}
