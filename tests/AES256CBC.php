<?php

use PHPUnit\Framework\TestCase;

use Clover\Classes\Crypt\AES256CBC as AES256CBC;

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
