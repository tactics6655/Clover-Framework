<?php

namespace Clover\Classes\XML;

class XMLParser
{

	private \XMLParser $parser;

	public function __construct(string|null $encoding = null)
	{
		$this->parser = xml_parser_create($encoding);
		$this->setObject();
	}

	public function setObject()
	{
		return xml_set_object($this->parser, $this);
	}

	public function parse($plainText)
	{
		return xml_parse($this->parser, $plainText);
	}

	public function freeParser()
	{
		return xml_parser_free($this->parser);
	}

	public function setElementHandler(callable $startTag, callable $endTag)
	{
		return xml_set_element_handler($this->parser, $startTag, $endTag);
	}

	public function setCharacterDataHandler($object)
	{
		return xml_set_character_data_handler($this->parser, $object);
	}
}
