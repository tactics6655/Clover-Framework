<?php

namespace Xanax\Exception\FileHandler;

use Xanax\Exception\FileHandler\IOException;

class InvalidFileHandler extends IOException 
{
	
	public function __construct(string $message = null, int $code = 0, \Exception $previous = null) 
	{
		parent::__construct($message, $code, $previous);
	}
	
}
