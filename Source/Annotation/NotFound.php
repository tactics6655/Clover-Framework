<?php

namespace Neko\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class NotFound
{
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
