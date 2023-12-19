<?php

namespace Xanax\Classes\XML;

class DOM
{

	private $dom;

	public function __construct()
	{
		$this->dom = new \DOMDocument;
	}

	public function parse($xmlString)
	{
		$this->dom->loadXML($xmlString);
	}

	public function isValid()
	{
		return isset($this->dom);
	}
}
