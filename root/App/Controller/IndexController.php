<?php

namespace App\Controller;

use Clover\Annotation;
use Clover\Framework\Component\BaseController;
use Clover\Classes\Maya;

#[Annotation\Prefix('/')]
#[Annotation\NotFound('IndexController::notFound')]
class IndexController extends BaseController
{

    #[Annotation\Route('GET', '/')]
    public function Index()
    {
        $this->addWebpackAssetsToHead('/App/Frontend/corejs/compiled/manifest.json');

        return $this->render('/App/Template/index.php');
    }

    public function notFound()
    {
        return $this->responseText('notFound');
    }

    #[Annotation\Route('GET', '/test')]
    public function Test()
    {
        return $this->responseText('test');
    }

}
