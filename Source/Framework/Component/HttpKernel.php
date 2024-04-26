<?php

namespace Clover\Framework\Component;

use App\Controller\IndexController;
use Clover\Classes\Routing\Router as Router;
use Clover\Classes\File\Functions as FileFunctions;
use Clover\Classes\Directory\Handler as DirectoryHandler;
use Clover\Classes\DependencyInjection\Container;
use Clover\Classes\OperationSystem;
use Clover\Enumeration\FileSizeUnit;
use Clover\Classes\Event\Dispatcher as EventDispatcher;

class HttpKernel
{
    private Container $container;

    private $environment;

    private $options;

    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher, Container $container, $environment = [], $options = [])
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->container = $container;
        $this->environment = $environment;
        $this->options = $options;
    }

    private function getEssentialBody($resource, $filePath)
    {
        return FileFunctions::getInterpretedContent($filePath, $resource);
    }

    /**
     * Run http kernel
     */
    public function run()
    {
        $router = new Router();

        $router->fromDirectory(__ROOT__ . '/App/Controller');

        $router->setContainer($this->container);

        $response = $router->handle();

        if (empty($response)) {
            exit('');
        }

        if (!$response instanceof Response) {
            throw new \Exception("Response is must be type of response, response data is '{$response}'");
        }

        if ($response->getType() == 'html') {
            $resources = $response->getResource();

            $header = $this->getEssentialBody($resources, __DIR__ . '/../Template/Header.php');
            $response->preAppendBody($header);

            $footer = $this->getEssentialBody($resources, __DIR__ . '/../Template/Footer.php');
            $response->appendBody($footer);

            $debuggerInformation = [
                'memoryUsage' => FileFunctions::formatSize(OperationSystem::getMemoryUsage(), FileSizeUnit::SHORT),
                'phpVersion' => OperationSystem::getPHPVersion(),
                'serverSoftware' => OperationSystem::getMainServerSoftware(),
                'builtOperationSystem' => OperationSystem::getBuiltOperationSystemString(),
                'freeSpace' => FileFunctions::formatSize(DirectoryHandler::getFreeSpace(), FileSizeUnit::SHORT),
                'environment' => $this->environment
            ];

            $response->appendBody(FileFunctions::getInterpretedContent(__DIR__ . '/../Template/Debugger.php', $debuggerInformation));
        }

        return $response;
    }
}
