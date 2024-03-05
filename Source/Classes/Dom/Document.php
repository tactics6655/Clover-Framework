<?php

namespace Clover\Classes\Dom;

use DomDocument;

class Document
{
    private $dom;

    public function __construct($encoding = 'UTF-8')
    {
        $this->dom = new DomDocument('1.0', $encoding);
        $this->dom->validateOnParse = true;
    }

    public function loadHTML(string $source, int $options = 0)
    {
        var_dump([$source]);
        $this->dom->loadXML($source, $options);
    }
}
