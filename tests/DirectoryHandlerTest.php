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
		$this->assertGreaterThan(1, $this->factory->getFileCount(__DIR__ . "\\"));
	}
}
