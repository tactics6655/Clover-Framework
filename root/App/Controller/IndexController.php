<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware;
use Clover\Annotation;
use Clover\Classes\HTTP\Request;
use Clover\Framework\Component\BaseController;
use Clover\Classes\System;

#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
    #[Annotation\Route(method: 'GET', pattern: '/')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function index(Request $request)
    {
        $this->addJsFileToHead('/App/Frontend/dist/coreJS.js');

        $text = $request->getQueryParamter('test');

        System\Output::printFormat("%s!!", $text);

        return $this->render('/App/View/test.php');
    }
}
