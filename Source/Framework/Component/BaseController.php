<?php

namespace Xanax\Framework\Component;

use Xanax\Framework\Component\Response;

class BaseController
{

    private $container;

    public function __construct($container = [])
    {
        $this->container = $container;
    }

    public function response($body, $resource = array())
    {
        return new Response($body, $resource);
    }
    
    public function addHeadCssFile($filename)
    {
        $this->container->Get('Resource')->addGenericCssFile($filename);
    }

    public function addHeadJsFile($filename)
    {
        $this->container->Get('Resource')->addGenericJavascriptFile($filename);
    }

    public function setTitle($title)
    {
        $this->container->Get('Resource')->setTitle($title);
    }

    public function render(string $template, array $data = [])
    {
        $resources = $this->container->Get('Resource')->extract();

        $render = $this->container->Get('Renderer')->render($template, $data);

        return $this->response($render, $resources);
    }

}