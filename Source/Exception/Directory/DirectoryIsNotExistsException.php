<?php

namespace Neko\Exception\DirectoryHandler;

use Neko\Exception\FileHandler\IOException;

class DirectoryIsNotExistsException extends IOException
{

	public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
