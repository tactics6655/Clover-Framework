<?php

declare(strict_types=1);

namespace Neko\Classes\Protocol;

use Neko\Enumeration\PHPProtocol;

class PHP
{
	public function getStandardError()
	{
		return PHPProtocol::STANDARD_ERROR;
	}

	public function getStandardOutput()
	{
		return PHPProtocol::STANDARD_OUTPUT;
	}

	public function getStandardInput()
	{
		return PHPProtocol::STANDARD_INPUT;
	}

	public function getFilter()
	{
		return PHPProtocol::FILTER;
	}

	public function getTemporary()
	{
		return PHPProtocol::TEMPORARY;
	}

	public function getMemory()
	{
		return PHPProtocol::MEMORY;
	}

	public function getInput()
	{
		return PHPProtocol::INPUT;
	}

	public function getOutput()
	{
		return PHPProtocol::OUTPUT;
	}
}
