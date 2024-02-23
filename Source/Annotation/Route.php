<?php

namespace Neko\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Route
{
    public $middleware;

    public $holder;

    public $method;

    public $pattern;

    public $notFoundHandler;

    public $contentType;

    public $host;

    public function __construct(string $method = "*", string $pattern = "*", string $host = "*")
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->host = $host;
    }
}
