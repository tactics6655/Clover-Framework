<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware as ModuleMiddleware;

use Neko\Annotation;
use Neko\Classes\Data\StringObject;
use Neko\Framework\Component\BaseController;
use Neko\Classes\Database\Driver\PHPDataObject;
use Neko\Classes\Database\Driver\SqLite;
use Neko\Classes\Upload\Handler as UploadHandler;
use Neko\Enumeration\HTTPRequestMethod as HTTPRequestMethod;

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
    public function index()
    {
        $str = new StringObject('TEST.');
        $str = $str->quotemeta()->split('');
        $str = $str->filter(function ($a) {
            return $a == 'T';
        });
        $str = $str->join('');

        return $this->response($str);
    }
}
