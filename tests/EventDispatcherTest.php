<?php

use PHPUnit\Framework\TestCase;

use Neko\Classes\File\Handler as FileHandler;
use Neko\Classes\File\Object as FileObject;

class EventDispatcherTest extends TestCase
{

	protected $factory;

	public function setUp(): void
	{
		$this->factory = new Neko\Classes\File\Handler();

		$this->factory->write(__DIR__."/testFile.txt", "testSuccess");
	}

	protected function tearDown(): void
    {
		$this->factory->delete(__DIR__."/testFile.txt");
	}

	public function testFileCount()
	{
		$this->assertSame("testSuccess", $this->factory->readAllContent(__DIR__ . "/testFile.txt"));
	}
}
