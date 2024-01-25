<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware as ModuleMiddleware;

use Neko\Annotation;
use Neko\Framework\Component\BaseController;
use Neko\Classes\Database\Driver\PHPDataObject;
use Neko\Classes\Upload\Handler as UploadHandler;

#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
    private function getConnection()
    {
        $pdo = new PHPDataObject();
        $pdo->setDatabase('company');
        $pdo->setHostName('172.18.0.2');
        $pdo->setUsername('root');
        $pdo->setPassword('1234');
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

    #[Annotation\Route('POST', '/excel_upload')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function excel_upload(UploadHandler $uploadHandler)
    {
        $fileName = "";

        if (!$uploadHandler->hasItem()) {
            $this->responseJson(['message' => '파일을 업로드 할 수 없습니다']);
        }

        if ($uploadHandler->hasError('excel')) {
            $this->responseJson(['error' => $uploadHandler->getFileError('excel')]);
        }

        $extension = $uploadHandler->getExtension('excel');
        $fileName = $uploadHandler->getFileName('excel');
        $isUploaded = $uploadHandler->move('excel', './'.$fileName);

        $this->responseJson(['text' => 'json', 'file_name' => $fileName, 'full' => $uploadHandler->get('excel')]);
    }

    #[Annotation\Route('GET', '/excel')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function excel()
    {
        return $this->render('App/Template/excel_upload.php');
    }

    #[Annotation\Route('GET', '/{company_name}/{business_code}')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function search($companyName, $businessCode)
    {
        $this->setTitle('test');
        $this->addJsFileToHead('/App/Template/base.js');
        $this->addCssFileToHead('/App/Template/layout.css');

        $parameters = [
            'company_name' => urldecode($companyName),
            'business_code' => $businessCode
        ];

        $pdo = $this->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM companies WHERE company_name = :company_name AND business_code = :business_code');
        $stmt->execute($parameters);

        $fetch = $stmt->fetchAll();

        return $this->render('App/Template/companies.php', [
            'companies' => $fetch, ...$parameters
        ]);
    }
}
