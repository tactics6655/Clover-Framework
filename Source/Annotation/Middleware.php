<?php

namespace Clover\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Middleware
{
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
