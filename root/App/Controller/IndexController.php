<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware as ModuleMiddleware;

use Neko\Annotation;
use Neko\Framework\Component\BaseController;
use Neko\Classes\Database\Driver\PHPDataObject;
use Neko\Classes\Database\Driver\SqLite;
use Neko\Classes\Upload\Handler as UploadHandler;
use Neko\Enumeration\HTTPRequestMethod as HTTPRequestMethod;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

#[Annotation\Prefix('/')]
#[Annotation\Middleware(ModuleMiddleware::class)]
class IndexController extends BaseController
{

    #[Annotation\Route('GET', '/')]
    public function index()
    {
        return $this->response('test');
    }
}
