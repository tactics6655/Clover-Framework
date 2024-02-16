<?php

namespace Neko\Framework\Component;

use Neko\Classes\Routing\Router as Router;
use Neko\Classes\File\Functions as FileFunctions;
use Neko\Classes\Directory\Handler as DirectoryHandler;
use Neko\Classes\DependencyInjection\Container;
use Neko\Classes\OperationSystem;
use Neko\Enumeration\FileSizeUnit;
use Neko\Classes\Event\Dispatcher as EventDispatcher;

class CliKernel
{
    private Container $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        echo 'not implements';
    }
}