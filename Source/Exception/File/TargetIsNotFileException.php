<?php

namespace Clover\Exception\FileHandler;

use Clover\Exception\FileHandler\IOException;

class TargetIsNotFileException extends IOException
{

	public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
