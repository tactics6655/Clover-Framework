<?php

namespace Clover\Exception\FileHandler;

class IOException extends \RuntimeException
{

	public function __construct(string $message, int $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
