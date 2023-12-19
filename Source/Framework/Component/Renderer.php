<?php

namespace Xanax\Framework\Component;

use Xanax\Classes\File\Functions as FileFunction;

class Renderer
{
    public function render(string $template, array $data = []): string
    {
        $render = FileFunction::getInterpretedContent($template, $data);

        return $render;
    }
}
