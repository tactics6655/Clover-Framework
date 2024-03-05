<?php

namespace Clover\Framework\Component;

use Clover\Classes\File\Functions as FileFunction;

class Renderer
{
    public function render(string $template, array $data = []): string
    {
        $render = FileFunction::getInterpretedContent($template, $data);

        return $render;
    }
}
