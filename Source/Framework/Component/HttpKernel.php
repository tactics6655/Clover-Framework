<?php

namespace Neko\Framework\Component;

use Neko\Classes\Routing\Router;
use Neko\Classes\File\Functions as FileFunctions;
use Neko\Classes\DependencyInjection\Container;

class HttpKernel
{
    private Container $container;

    private $environment;

    private $options;

    public function __construct(Container $container, $environment = [], $options = [])
    {
        $this->container = $container;
        $this->environment = $environment;
        $this->options = $options;
    }

    private function getEssentialBody($resource, $filePath)
    {
        return FileFunctions::getInterpretedContent($filePath, $resource);
    }

    public function run()
    {
        $router = new Router();

        $router->fromDirectory('./App/Controller');

        $router->setContainer($this->container);

        $response = $router->handle();

        if (empty($response)) {
            exit();
        }

        if (!$response instanceof Response) {
            throw new \Exception('Response is must be type of response');
        }

        $header = $this->getEssentialBody($response->getResource(), __DIR__ . '/../Template/Header.php');
        $response->preAppendBody($header);

        $footer = $this->getEssentialBody($response->getResource(), __DIR__ . '/../Template/Footer.php');
        $response->appendBody($footer);

        return $response;
    }
}
