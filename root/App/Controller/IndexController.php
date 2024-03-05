<?php

namespace App\Controller;

use FVP;
use App\Middleware\ModuleMiddleware;
use Clover\Annotation;
use Clover\Classes\Data\ArrayObject;
use Clover\Classes\HTTP\Request;
use Clover\Framework\Component\BaseController;
use Clover\Classes\File\Functions as FileFunctions;
use Clover\Classes\Upload\Handler as UploadHandler;
use Clover\Framework\Component\Renderer;
use Clover\Enumeration;

#[Annotation\Prefix('/')]
#[Annotation\NotFound('IndexController::notFound')]
class IndexController extends BaseController
{

    public function notFound()
    {
        return $this->response('404 Error');
    }

    #[Annotation\Route(method: 'GET', pattern: '/{test}:(alphabet)/{test2}:(number)')]
    public function test(UploadHandler $handler)
    {
        return $this->render('/App/View/view.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/')]
    #[Annotation\Middleware(ModuleMiddleware::class)]
    public function index(Request $request, Renderer $renderer)
    {
        $readed = FileFunctions::read(__ROOT__."/App/File/sakura.txt");

        $fvpParser = new FVP\FVPParser($readed);
        $fvpParser->parse();

        return $this->render('/App/View/view.php');
    }

    #[Annotation\ContentType(Enumeration\ContentType::MULTIPART_FORM_DATA)]
    #[Annotation\Route(method: 'POST', pattern: '/')]
    public function upload_index(UploadHandler $uploadFile)
    {
        $fileContextName = "file";

        if ($uploadFile->isEmpty()) {
            return $this->responseJson(['message' => 'Upload file does not found']);
        }

        if ($uploadFile->hasError($fileContextName)) {
            return $this->responseJson(['message' => $uploadFile->getFileErrorMessage($fileContextName)]);
        }

        if (!$uploadFile->hasItem()) {
            return $this->responseJson(['message' => 'Upload file does not found']);
        }

        if (!$uploadFile->inExtension($fileContextName, ['exe', 'jpg'])) {
            return $this->responseJson(['message' => 'Extension of file is not allowed']);
        }

        if ($uploadFile->move($fileContextName, __ROOT__)) {
            return $this->responseJson(['message' => 'Failed to upload file into target directory']);
        }

        return $this->responseJson(['message' => 'Success to upload file']);
    }
}
