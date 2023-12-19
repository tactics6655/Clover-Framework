<?php

use PHPUnit\Framework\TestCase;

use Xanax\Classes\HTTP\Request as HTTPRequest;

class HTTPRequestTest extends TestCase
{

	public function setUp(): void
	{
	}

	public function testFailure(): void
	{
		$this->assertEquals(HTTPRequest::getRequestURL()->__toString(), "http://");
	}
}
