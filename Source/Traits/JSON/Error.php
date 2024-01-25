<?php

namespace Neko\Trait\Json;

use Neko\Enumeration\JsonErrorMessage;

trait JSONError
{

	public function getLastError()
	{
		return json_last_error();
	}

	public function getLastErrorMessage()
	{
		return json_last_error_msg();
	}

	public function hasError()
	{
		return $this->getLastError() !== JSON_ERROR_NONE;
	}

	public function hasSyntaxError()
	{
		return $this->getLastError() !== JSON_ERROR_SYNTAX;
	}

	public function isMalformed()
	{
		// TODO
		return $this->getLastError() === JSON_ERROR_STATE_MISMATCH;
	}

	public function isInvalid()
	{
		// TODO
		return $this->getLastError() === JSON_ERROR_STATE_MISMATCH;
	}

	public function isStackDepthExceeded()
	{
		return $this->getLastError() === JSON_ERROR_DEPTH;
	}

	// PHP 7

	public function isMalformedUTF8()
	{
		return $this->getLastError() === JSON_ERROR_UTF8;
	}

	public function getMessage()
	{
		switch ($this->getLastError()) {
			case JSON_ERROR_DEPTH:
				return JsonErrorMessage::JSON_ERROR_DEPTH;
			case JSON_ERROR_STATE_MISMATCH:
				return JsonErrorMessage::JSON_ERROR_STATE_MISMATCH;
			case JSON_ERROR_CTRL_CHAR:
				return JsonErrorMessage::JSON_ERROR_CTRL_CHAR;
			case JSON_ERROR_SYNTAX:
				return JsonErrorMessage::JSON_ERROR_SYNTAX;
			case JSON_ERROR_UTF8:
				return JsonErrorMessage::JSON_ERROR_UTF8;
			default:
				return JsonErrorMessage::JSON_ERROR_UNKNOWN;
		}
	}
}
