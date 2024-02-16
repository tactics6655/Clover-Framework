<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware as ModuleMiddleware;

use Neko\Annotation\Middleware;
use Neko\Annotation\Route;
use Neko\Annotation\Prefix;
use Neko\Classes\Data\ArrayObject;
use Neko\Classes\Data\StringObject;
use Neko\Framework\Component\BaseController;
use Neko\Classes\Database\Driver\SqLite;
use Neko\Classes\File\Functions;
use Neko\Classes\HTTP\Request;

use Neko\Classes\Tokenizer;

#[Prefix('/')]
class IndexController extends BaseController
{

    #[Route('GET', '/')]
    public function index()
    {
        return $this->response('Hello World');
    }
}
