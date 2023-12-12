<?php

use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Framework\Component\BaseController;
use Xanax\Framework\Component\Middleware\ModuleMiddleware;

use Xanax\Annotation;

#[Annotation\Prefix('/')]
#[Annotation\Middleware(ModuleMiddleware::class)]
class ModuleController extends BaseController
{
    #[Annotation\Route('GET', '/{mid}')]
    public function index(HTTPRequest $request, $mid)
    {
        $ip = $request->getRemoteIP();

        $this->setTitle('test');
        $this->addHeadJsFile('jquery.js');
        $this->addHeadCssFile('test.css');

        return $this->render('App/Template/test.php', ['ip' => $ip]);
    }

}