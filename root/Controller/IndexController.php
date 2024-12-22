<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware as ModuleMiddleware;

use Clover\Annotation;
use Clover\Framework\Component\BaseController;
use Clover\Classes\Database\Driver\PHPDataObject;
use Clover\Classes\Upload\Handler as UploadHandler;

#[Annotation\Prefix('/')]
#[Annotation\Middleware(ModuleMiddleware::class)]
class IndexController extends BaseController
{
    private function getConnection()
    {
        $pdo = new PHPDataObject();
        $pdo->setDatabase('company');
        $pdo->setHostName('localhost');
        $pdo->setUsername('bnccert1');
        $pdo->setPassword('k40313031005');
        $pdo->setPort('3306');
        $pdo->connect();

        return $pdo;
    }

    #[Annotation\Route('GET', '/')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function index()
    {
        $this->setTitle('test');
        $this->addJsFileToHead('/App/Template/base.js');
        $this->addCssFileToHead('/App/Template/layout.css');
        
        return $this->render('App/Template/companies.php', ['companies' => null]);
    }
}
