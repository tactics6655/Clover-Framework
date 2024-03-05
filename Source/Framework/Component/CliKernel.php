<?php

namespace Clover\Framework\Component;

use Clover\Classes\Routing\Router as Router;
use Clover\Classes\File\Functions as FileFunctions;
use Clover\Classes\Directory\Handler as DirectoryHandler;
use Clover\Classes\DependencyInjection\Container;
use Clover\Classes\OperationSystem;
use Clover\Classes\Event\Dispatcher as EventDispatcher;
use Clover\Enumeration\FileSizeUnit;

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
