<?php

namespace Clover\Annotation;

use Clover\Enumeration\ContentType as ContentTypeEnum;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class ContentType
{
    public $value;

    public function __construct(ContentTypeEnum $value)
    {
        $this->value = $value->value;
    }
}
