<?php

namespace Xanax\Framework\Component;

class Response
{

    private $body;

    private $resource;

    public function __construct($body, $resource = array())
    {
        $this->body = $body;
        $this->resource = $resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function printBody()
    {
        echo $this->body;
    }

    public function preAppendBody($body)
    {
        $this->body = $body . $this->body;
    }

    public function appendBody($body)
    {
        $this->body .= $body;
    }
}
