<?php

use PHPUnit\Framework\TestCase;

use Xanax\Classes\Format\MultiPurposeInternetMailExtensions as MIME;

class MIMETest extends TestCase
{

	public function setUp(): void
	{
	}

	public function testFailure(): void
	{
		$this->assertEquals(MIME::getContentTypeFromExtension('exe'), "application/vnd.microsoft.portable-executable");
		$this->assertEquals(MIME::getContentTypeFromExtension('zip'), "application/zip");
		$this->assertEquals(MIME::getContentTypeFromExtension('markdown'), "text/markdown");
	}
}
