<?php

namespace Xanax\Framework\Component;

use Xanax\Classes\HTTP\Router as Router;
use Xanax\Classes\File\Functions as FileFunctions;

class HttpKernel
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    private function getEssentialBody($resource, $filePath)
    {
        return FileFunctions::getInterpretedContent($filePath, $resource);
    }

    public function run()
    {
        Router::fromDirectory('./App/Controller');

        Router::setContainer($this->container);

        $response = Router::run();

        if (empty($response)) {
            exit();
        }

        if (!$response instanceof Response) {
            throw new \Exception('Response is must be response type');
        }

        $header = $this->getEssentialBody($response->getResource(), __DIR__ . '/../Template/Header.php');
        $response->preAppendBody($header);

        $footer = $this->getEssentialBody($response->getResource(), __DIR__ . '/../Template/Footer.php');
        $response->appendBody($footer);

        return $response;
    }
}
