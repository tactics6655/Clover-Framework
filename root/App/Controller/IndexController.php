<?php

namespace App\Controller;

use Neko\Annotation\NotFound;
use Neko\Annotation\ContentType;
use Neko\Annotation\Route;
use Neko\Annotation\Prefix;
use Neko\Framework\Component\BaseController;
use Neko\Classes\Upload\Handler as UploadHandler;
use Neko\Enumeration\ContentType as ContentTypeEnum;
use Neko\Classes\Maya;


#[Prefix('/')]
#[NotFound('App\Controller\IndexController::notFound')]
class IndexController extends BaseController
{

    public function notFound()
    {
        return $this->response('404 Error');
    }

    #[Route(method: 'GET', pattern: '/{test}:(alphabet)/{test2}:(number)')]
    public function test(UploadHandler $handler)
    {
        return $this->render('/App/View/view.php');
    }

    #[Route(method: 'GET', pattern: '/')]
    public function index()
    {test
        return $this->render('/App/View/view.php');
    }

    #[ContentType(ContentTypeEnum::MULTIPART_FORM_DATA->value)]
    #[Route(method: 'POST', pattern: '/')]
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

        if (!in_array($uploadFile->getExtension($fileContextName), ['exe', 'jpg'])) {
            return $this->responseJson(['message' => 'Extension of file is not allowed']);
        }

        if ($uploadFile->move($fileContextName, __ROOT__)) {
            return $this->responseJson(['message' => 'Failed to upload file into target directory']);
        }

        return $this->responseJson(['message' => 'Success to upload file']);
    }
}
