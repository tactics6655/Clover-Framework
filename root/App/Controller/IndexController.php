<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware;
use Clover\Annotation;
use Clover\Classes\HTTP\Request;
use Clover\Classes\System\Output;
use Clover\Framework\Component\BaseController;

#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
    #[Annotation\Route(method: 'GET', pattern: '/')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function index(Request $request)
    {
        $this->addJsFileToHead('/App/Frontend/corejs/coreJS.min.js');

        $scheme = $request->getScheme();

        Output::print($scheme);

        return $this->render('/App/View/test.php');
    }
}
