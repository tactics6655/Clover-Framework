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

#[Annotation\NotFound('IndexController::notFound')]
#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
	
	#[Annotation\Route(method: 'GET', pattern: '/')]
	public function index()
	{
		$this->addCssFileToHead('/App/Resource/layout.css');
		return $this->render('/App/View/index.php', []);
	}

	#[Annotation\Route(method: 'GET', pattern: '/clover')]
	public function clover()
	{
		$this->setTitle('Clover Framework');

		$this->addCssFileToHead('https://fonts.googleapis.com/css?family=Titillium Web');
		$this->addWebpackAssetsToHead('/App/Frontend/corejs/compiled/manifest.json');

		return $this->render('/App/View/clover/index.php');
	}

	public function notFound()
	{
		return $this->debug("404 Not Found");
	}
}
