<?php

use PHPUnit\Framework\TestCase;

use Xanax\Classes\File\Handler as FileHandler;
use Xanax\Classes\File\Object as FileObject;

class FileHandlerTest extends TestCase {
	
	protected $factory;
	
    public function setUp() :void
	{
		$this->factory = new Xanax\Classes\File\Handler();
	}
	
	public function testFileCount() 
	{
		$this->assertSame("testSuccess", $this->factory->readAllContent(__DIR__."/testFile.txt"));
	}
	
}