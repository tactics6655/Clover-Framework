<?php

namespace Neko\Framework\Component;

use Neko\Framework\Component\Response;

use Neko\Classes\DependencyInjection\Container;
use Neko\Classes\ContentType as ContentType;
use Neko\Classes\Data\JSONHandler;
use Neko\Classes\Header;

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
        /** @var \Neko\Framework\Component\Resource $resource */
        $resource = $this->container->get('Resource');

        $resource->addGenericCssFile($filename);
    }

    public function addJsFileToHead($filename)
    {
        /** @var \Neko\Framework\Component\Resource $resource */
        $resource = $this->container->get('Resource');

        $resource->addGenericJavascriptFile($filename);
    }

    /**
     * Set a title in browser
     */
    public function setTitle($title)
    {
        /** @var \Neko\Framework\Component\Resource $resource */
        $resource = $this->container->get('Resource');

        $resource->setTitle($title);
    }

    public function redirect($location)
    {
        Header::responseRedirectLocation($location);
    }

    public function responseJson($json, $resource = array())
    {
        $encoded = JSONHandler::encode($json);

        if (!JSONHandler::isJSON($encoded)) {
            return false;
        }

        return new Response($encoded, $resource, 'json');
    }

    public function render(string $template, array $data = [])
    {
        /** @var \Neko\Framework\Component\Resource $resource */
        $resource = $this->container->get('Resource');

        $resources = $resource->extract();

        /** @var \Neko\Framework\Component\Renderer $renderer */
        $renderer = $this->container->get('Renderer');

        $render = $renderer->render($template, $data);

        return $this->response($render, $resources);
    }
}
