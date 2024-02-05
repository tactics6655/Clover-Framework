<?php

namespace Neko\Classes\XML;

use SimpleXMLElement;

class SimpleXML
{

	private SimpleXMLElement|bool $data;

	public function __construct()
	{
	}

	public function hasChildren()
	{
		return $this->data->hasChildren();
	}

	public function getData()
	{
		return $this->data;
	}

	public function parse($text)
	{
		$this->data = simplexml_load_string($text);
	}

	public function fromFile($filePath)
	{
		$this->data = simplexml_load_file($filePath);
	}

	public function isValid()
	{
		if ($this->data == null || !$this->data) {
			return false;
		}

		return true;
	}

	public function getChildren(string|null $namespaceOrPrefix = null, bool|null $isPrefix = false)
	{
		$this->data = $this->data->children();

		return $this;
	}
}
