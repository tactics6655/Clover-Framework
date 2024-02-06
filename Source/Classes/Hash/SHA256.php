<?php

declare(strict_types=1);

namespace Neko\Classes\Hash;

class SHA256 extends Handler
{
	public function __construct()
	{
		parent::__construct('sha256');
	}
}
