<?php

namespace Neko\Framework\Component;

use Neko\Classes\File\Functions as FileFunction;

class Renderer
{
    public function render(string $template, array $data = []): string
    {
        $render = FileFunction::getInterpretedContent($template, $data);

        return $render;
    }
}
