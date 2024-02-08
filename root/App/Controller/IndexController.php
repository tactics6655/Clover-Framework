<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware as ModuleMiddleware;

use Neko\Annotation;
use Neko\Classes\Data\StringObject;
use Neko\Framework\Component\BaseController;
use Neko\Classes\Database\Driver\PHPDataObject;
use Neko\Classes\Database\Driver\SqLite;
use Neko\Classes\HTTP\Request;
use Neko\Classes\Upload\Handler as UploadHandler;
use Neko\Enumeration\HTTPRequestMethod as HTTPRequestMethod;
use Neko\Framework\Component\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

#[Annotation\Prefix('/test')]
#[Annotation\Middleware(ModuleMiddleware::class)]
class IndexController extends BaseController
{

    /**
     * Main Page
     */
    #[Annotation\Route('GET', '/')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function index(Request $request)
    {
        return $this->response($request->getRemoteIPAddress()->shuffle()->removeNullByte());
    }
}
