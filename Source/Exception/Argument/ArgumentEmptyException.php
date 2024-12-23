<?php

namespace Clover\Exception\Argument;

use Clover\Exception\FileHandler\IOException;

class ArgumentEmptyException extends IOException
{

	public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
