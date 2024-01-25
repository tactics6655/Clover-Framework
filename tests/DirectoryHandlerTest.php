<?php

use PHPUnit\Framework\TestCase;

use Neko\Classes\Directory\Handler as DirectoryHandler;

class DirectoryHandlerTest extends TestCase
{

	protected $factory;

	public function setUp(): void
	{
		$this->factory = new DirectoryHandler();
	}

	public function testFileCount()
	{
		$this->assertSame("testSuccess", $this->factory->getFileCount(__DIR__ . "/testFile.txt"));
	}
}
