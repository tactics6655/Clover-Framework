<?php

use PHPUnit\Framework\TestCase;

use Xanax\Classes\AES256CBC as AES256CBC;

class AES256CBCTest extends TestCase
{

	public function setUp(): void
	{
	}

	public function testFailure(): void
	{
		$this->assertEquals(MIME::getContentTypeFromExtension('exe'), "application/vnd.microsoft.portable-executable");
	}
}
