<?php

namespace Neko\Framework\Component;

use Neko\Framework\Component\Response;

use Neko\Classes\DependencyInjection\Container;
use Neko\Classes\ContentType as ContentType;
use Neko\Classes\Data\JSONHandler;

class BaseController
{

    public function __construct(private ?Container $container = null)
    {
        $this->container = $container;
    }

    public function response($body, $resource = array())
    {
        return new Response($body, $resource);
    }

    public function addCssFileToHead($filename)
    {
        $this->container->get('Resource')->addGenericCssFile($filename);
    }

    public function addJsFileToHead($filename)
    {
        $this->container->get('Resource')->addGenericJavascriptFile($filename);
    }

    public function setTitle($title)
    {
        $this->container->get('Resource')->setTitle($title);
    }

    public function responseJson($json)
    {
        $encoded = JSONHandler::encode($json);

        if (!JSONHandler::isJSON($encoded)) {
            return false;
        }

        echo $encoded;
        exit();
    }

    public function render(string $template, array $data = [])
    {
        $resources = $this->container->get('Resource')->extract();

        $render = $this->container->get('Renderer')->render($template, $data);

        return $this->response($render, $resources);
    }
}
