<?php

namespace Clover\Exception\ResourceHandler;

use Clover\Exception\FileHandler\IOException;

class InvalidTypeException extends IOException
{

	public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
