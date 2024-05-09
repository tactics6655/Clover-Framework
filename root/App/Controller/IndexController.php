<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware;
use Clover\Annotation;
use Clover\Classes\ArraySummarizer;
use Clover\Classes\Data\ArrayObject;
use Clover\Classes\Data\IntegerObject;
use Clover\Classes\Data\StringObject;
use Clover\Classes\Database\Driver\SqLite as SqLite;
use Clover\Classes\HTTP\Request;
use Clover\Framework\Component\BaseController;
use Clover\Classes\File\Functions as FileFunctions;
use Clover\Classes\Directory\Handler as DirectoryHandler;
use Clover\Classes\Image\Handler;
use Clover\Enumeration\MIME;

#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
    #[Annotation\Route(method: 'GET', pattern: '/')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function index(Request $request)
    {
        return $this->renderText('');
    }
}
