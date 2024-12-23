<?php

declare(strict_types=1);

namespace Clover\Classes;

class Encode
{
	public function detect($string)
	{
		$encoding = mb_detect_encoding($string, mb_list_encodings(), true);

		return $encoding;
	}
}
