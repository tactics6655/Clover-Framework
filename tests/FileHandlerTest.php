<?php

use PHPUnit\Framework\TestCase;

use Clover\Classes\File\Handler as FileHandler;
use Clover\Classes\File\Object as FileObject;

class FileHandlerTest extends TestCase
{

	/** @var Clover\Classes\File\Handler $factory */
	protected $factory;

	public function setUp(): void
	{
		$this->factory = new Clover\Classes\File\Handler();

		$this->factory->write(__DIR__."/testFile.txt", "testSuccess");
	}

	protected function tearDown(): void
    {
		$this->factory->delete(__DIR__."/testFile.txt");
	}

	public function testType()
	{
		$this->assertSame("file", $this->factory->getType(__DIR__ . "/testFile.txt"));
	}

	public function testGetSize()
	{
		$this->assertIsInt($this->factory->getSize(__DIR__ . "/testFile.txt"));
	}

	public function testBaseName()
	{
		$this->assertSame("testFile.txt", $this->factory->getBasename(__DIR__ . "/testFile.txt"));
	}

	public function testReadAllContent()
	{
		$this->assertSame("testSuccess", $this->factory->readAllContent(__DIR__ . "/testFile.txt"));
	}

	public function getExtensionByFilePath()
	{
		$this->assertSame("txt", $this->factory->getExtension(__DIR__ . "/testFile.txt"));
	}
	
	public function testGetExtension()
	{
		$this->assertSame("txt", $this->factory->getExtension(__DIR__ . "/testFile.txt"));
	}
	
	public function testGetInode()
	{
		$this->assertIsInt($this->factory->getInode(__DIR__ . "/testFile.txt"));
	}
	
	public function testGetCraetedDate()
	{
		$this->assertIsInt($this->factory->getCreatedDate(__DIR__ . "/testFile.txt"));
	}
	
	public function testGetLastAccessDate()
	{
		$this->assertIsInt($this->factory->getLastAccessDate(__DIR__ . "/testFile.txt"));
	}
	
	public function testContainFolder()
	{
		$this->assertTrue($this->factory->isContainFolder(__DIR__, __DIR__."/testFile.php"));
	}
}
