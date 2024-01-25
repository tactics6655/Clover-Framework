<?php

use PHPUnit\Framework\TestCase;

use Neko\Classes\File\Handler as FileHandler;
use Neko\Classes\File\Object as FileObject;

class FileHandlerTest extends TestCase
{

	protected $factory;

	public function setUp(): void
	{
		$this->factory = new Neko\Classes\File\Handler();
	}

	public function testFileCount()
	{
		$this->assertSame("testSuccess", $this->factory->readAllContent(__DIR__ . "/testFile.txt"));
	}
}
