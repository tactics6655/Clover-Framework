<?php

namespace Neko\Traits\Regex;

use function preg_last_error_constant;

trait RegexError
{

	public function getErrorConstant()
	{
		if (function_exists("preg_last_error_constant")) {
			return preg_last_error_constant();
		}

		return false;
	}

	public function getErrorCode()
	{
		return preg_last_error();
	}

	public function hasJITStackLimitError()
	{
		return PREG_JIT_STACKLIMIT_ERROR !== $this->getErrorCode();
	}

	public function hasBadUTF8OffsetError()
	{
		return PREG_BAD_UTF8_OFFSET_ERROR !== $this->getErrorCode();
	}

	public function hasBadUTF8Error()
	{
		return PREG_BAD_UTF8_ERROR !== $this->getErrorCode();
	}

	public function hasRecursionLimitEror()
	{
		return PREG_RECURSION_LIMIT_ERROR !== $this->getErrorCode();
	}

	public function hasBacktrackLimitError()
	{
		return PREG_BACKTRACK_LIMIT_ERROR !== $this->getErrorCode();
	}

	public function hasInternalError()
	{
		return PREG_INTERNAL_ERROR !== $this->getErrorCode();
	}

	public function hasError()
	{
		return PREG_NO_ERROR !== $this->getErrorCode();
	}
}
