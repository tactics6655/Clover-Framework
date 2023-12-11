<?php

namespace Xanax\Framework\Component;

class BaseController
{

    private $container;

    public function __construct($container = [])
    {
        $this->container = $container;
    }

    public function render(string $template, array $data = [])
    {
        return $this->container->Get('Renderer')->render($template, $data);
    }

}