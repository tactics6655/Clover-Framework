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
        $this->container->get('Resource')->addGenericCssFile($filename);
    }

    public function addHeadJsFile($filename)
    {
        $this->container->get('Resource')->addGenericJavascriptFile($filename);
    }

    public function setTitle($title)
    {
        $this->container->get('Resource')->setTitle($title);
    }

    public function render(string $template, array $data = [])
    {
        $resources = $this->container->get('Resource')->extract();

        $render = $this->container->get('Renderer')->render($template, $data);

        return $this->response($render, $resources);
    }
}
