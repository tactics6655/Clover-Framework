<?php

use PHPUnit\Framework\TestCase;

use Neko\Classes\Crypt\AES256CBC as AES256CBC;

class AES256CBCTest extends TestCase
{

	public function setUp(): void
	{
	}

	public function testFailure(): void
	{
		$encrypt = AES256CBC::encrypt('test', 'P4lB5jeIzH3ei1elH6rIPZqvDhEDRgYc');
		$decrypt = AES256CBC::decrypt($encrypt, 'P4lB5jeIzH3ei1elH6rIPZqvDhEDRgYc');

		$this->assertEquals($decrypt, "test");

		$encrypt = AES256CBC::encrypt('password', 'P4lB5jeIzH3ei1elH6rIPZqvDhEDRgYc');
		$decrypt = AES256CBC::decrypt($encrypt, 'P4lB5jeIzH3ei1elH6rIPZqvDhEDRgYc');

		$this->assertEquals($decrypt, "password");
	}
}
