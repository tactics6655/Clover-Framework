<?php

namespace Xanax\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
final class Middleware
{
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}