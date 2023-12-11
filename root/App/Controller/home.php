<?php

use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Framework\Component\BaseController;

use Xanax\Annotation;

#[Annotation\Prefix('/')]
class HomeController extends BaseController
{
    #[Annotation\Route('GET', '/')]
    public function index(HTTPRequest $request)
    {
        $ip = $request->getRemoteIP();

        return $this->render('./App/Template/test.php', ['ip' => $ip]);
    }

}