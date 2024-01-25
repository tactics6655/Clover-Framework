<?php

namespace Neko\Classes\Regex;

use Neko\Traits\Regex\Error;

class StringResult
{
	private $boolean;

	private $pattern;

	private $subject;

	private $matches;

	public function __construct(array $result)
	{
		$this->boolean = $result['Boolean'];
		$this->pattern = $result['Pattern'];
		$this->subject = $result['Subject'];
		$this->matches = $result['Matches'];
	}

	public function getSingleton(array $result)
	{
		return new \static($result);
	}

	public function get()
	{
	}
}
