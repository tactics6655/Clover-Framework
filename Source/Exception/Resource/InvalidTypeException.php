<?php

namespace Neko\Exception\ResourceHandler;

use Neko\Exception\FileHandler\IOException;

class InvalidTypeException extends IOException
{

	public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
