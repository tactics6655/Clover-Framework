<?php

namespace App\Controller;

use App\Middleware\ModuleMiddleware;
use Clover\Annotation;
use Clover\Classes\Data\ArrayObject;
use Clover\Classes\Data\StringObject;
use Clover\Classes\Database\Driver\PHPDataObject;
use Clover\Classes\Directory\Handler as DirectoryHandler;
use Clover\Classes\File\Handler as FileHandler;
use Clover\Classes\File\Functions;
use Clover\Classes\HTTP\Request;
use Clover\Classes\System\Output;
use Clover\Framework\Component\BaseController;

#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
	#[Annotation\Route(method: 'GET', pattern: '/')]
	public function index()
	{
		$this->setTitle('Clover Framework');

		$this->addCssFileToHead('https://fonts.googleapis.com/css?family=Titillium Web');
		$this->addCssFileToHead('/App/Resource/css/reset/reset.css');
		$this->addCssFileToHead('/App/Resource/base.css');
		$this->addCssFileToHead('/App/Resource/js/highlight/styles/vs2015.min.css');
		$this->addJsFileToHead('/App/Frontend/corejs/coreJS.min.js');
		$this->addJsFileToHead('/App/Resource/js/highlight/highlight.min.js');

		return $this->render('/App/View/index.php');
	}
}
