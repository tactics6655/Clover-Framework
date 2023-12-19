<?php

use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Classes\Database\Driver\SqLite;
use Xanax\Classes\Pagination\Dynamic as DynamicPagination;
use Xanax\Classes\File\Functions;

use Xanax\Framework\Component\BaseController;
use Xanax\Framework\Component\Middleware\ModuleMiddleware;

use Xanax\Annotation;

use Xanax\Plugin\NaverPapago;

#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
    #[Annotation\Route('GET', '/translate')]
    public function translate()
    {
        $this->setTitle('translate');

        $papago = new NaverPapago('ek3aVfWELzBXr1iVeMoi', 'yK6OP2Asdh');
        $translate = $papago->translate('Hello World', 'en', 'ja');

        return $this->render('App/Template/test.php', ['ip' => $translate]);
    }

    #[Annotation\Route('GET', '/')]
    public function index(HTTPRequest $request)
    {
        $ip = $request->getRemoteIP();

        $this->setTitle('test');
        $this->addHeadJsFile('jquery.js');
        $this->addHeadCssFile('test.css');

        return $this->render('App/Template/test.php', ['ip' => $ip]);
    }
}
