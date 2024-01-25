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

    public function __construct(string $method, string $pattern)
    {
        $this->method = $method;
        $this->pattern = $pattern;
    }
}
