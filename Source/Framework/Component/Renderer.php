<?php

namespace Clover\Framework\Component;

use Clover\Classes\File\Functions as FileFunction;
use Clover\Classes\Data\ArrayObject;

class Renderer
{
    public function render(string $template, ArrayObject|array $data = []): string
    {
        $render = FileFunction::getInterpretedContent($template, $data);

        return $render;
    }
}
