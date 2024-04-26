<?php

namespace Clover\Framework\Component;

use Clover\Framework\Component\Response;
use Clover\Framework\Component\Resource;

use Clover\Classes\DependencyInjection\Container;
use Clover\Classes\ContentType as ContentType;
use Clover\Classes\Data\JSONHandler;
use Clover\Classes\Header;

class BaseController
{

    public function __construct(private ?Container $container = null)
    {
        $this->container = $container;
    }

    public function debug($body, $resource = array())
    {
        return new Response('<div style="white-space: break-spaces;font-size: 11px;">'.$body.'</div>', $resource);
    }

    public function response(mixed $body, $resource = array())
    {
        return new Response($body, $resource);
    }

    public function addCssFileToHead(string $filename)
    {
        /** @var Resource $resource */
        $resource = $this->container->get('Resource');

        $resource->addGenericCssFile($filename);
    }

    public function addJsFileToHead(string $filename)
    {
        /** @var Resource $resource */
        $resource = $this->container->get('Resource');

        $resource->addGenericJavascriptFile($filename);
    }

    /**
     * Set a title in browser
     * 
     * @param string $title
     */
    public function setTitle(string $title)
    {
        /** @var Resource $resource */
        $resource = $this->container->get('Resource');

        $resource->setTitle($title);
    }

    public function redirect($location)
    {
        Header::responseRedirectLocation($location);
    }

    /**
     * Response json data
     * 
     * @param array $json
     * @param array $resource
     * 
     * @return bool|Response
     */
    public function responseJson(array $json, array $resource = [])
    {
        $encoded = JSONHandler::encode($json);

        if (!JSONHandler::isJSON($encoded)) {
            return false;
        }

        return new Response($encoded, $resource, 'json');
    }

    public function render(string $template, array $data = [])
    {
        /** @var Resource $resource */
        $resource = $this->container->get('Resource');

        $extractedRecources = $resource->extract();

        /** @var Renderer $renderer */
        $renderer = $this->container->get('Renderer');

        $renderedContent = $renderer->render(__ROOT__ . $template, $data);

        return $this->response($renderedContent, $extractedRecources);
    }
}
