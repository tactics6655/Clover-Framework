<?php

namespace Clover\Framework\Component;

use Clover\Framework\Component\Response;
use Clover\Framework\Component\Resource;

use Clover\Classes\Data\ArrayObject;
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

    public function debug($body, $resource = [])
    {
        return new Response('<div style="white-space: break-spaces;font-size: 11px;">' . print_r($body, true) . '</div>', $resource);
    }

    public function responseText(mixed $body)
    {
        return new Response($body, [], 'text');
    }

    public function response(mixed $body, $resource = array())
    {
        return new Response($body, $resource, 'html');
    }

    public function addCssFileToHead(string $filename)
    {
        /** @var Resource $resource */
        $resource = $this->container->get('Resource');

        $resource->addGenericCssFile($filename);
    }

    public function addWebpackAssetsToHead(string $path)
    {
        /** @var Resource $resource */
        $resource = $this->container->get('Resource');

        $assets = $this->getWebpackAssets(sprintf("%s%s", __ROOT__, $path));

        foreach ($assets as $asset) {
            $dotPosition = strrpos($asset, '.');

            if ($dotPosition === false) {
                continue;
            }

            $extension = substr($asset, $dotPosition + 1);
            $assetPath = sprintf("%s%s", dirname($path), $asset);

            if (array_search($extension, ['js']) !== false) {
                $resource->addGenericJavascriptFile($assetPath);
            } else if (array_search($extension, ['css']) !== false) {
                $resource->addGenericCssFile($assetPath);
            }
        }
    }

    public function getWebpackAssets($path) {
        $manifest = json_decode(file_get_contents($path), true);
    
        return $manifest;
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

    public function renderText($content)
    {
        return $this->response($content);
    }

    public function render(string $template, ArrayObject|array $data = [])
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
