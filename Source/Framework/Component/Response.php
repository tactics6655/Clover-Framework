<?php

namespace Neko\Framework\Component;

use Neko\Classes\Header;

class Response
{
    private string $type;

    private mixed $body;

    private array $resource;

    private int $statusCode;

    private $protocolVersion = '1.0';

    public function __construct($body, $resource = array(), $type = 'html', $statusCode = 200)
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
