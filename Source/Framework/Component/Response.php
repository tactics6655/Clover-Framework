<?php

namespace Clover\Framework\Component;

use Clover\Classes\Header;
use Clover\Classes\System;

class Response
{
    private string $type;

    private mixed $body;

    private array $resource;

    private int $statusCode;

    private string $protocolVersion = '1.0';

    public function __construct($body, $resource = [], $type = 'html', $statusCode = 200)
    {
        $this->body = $body;
        $this->resource = $resource;
        $this->type = $type;
        $this->statusCode = $statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function sendHeaders()
    {
        if (Header::isSent()) {
            return false;
        }
    }

    public function printBody()
    {
        System\Output::print($this->body);
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
