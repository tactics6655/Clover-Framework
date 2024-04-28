<?php

namespace App\Controller;

use Clover\Classes\DependencyInjection\Container;
use App\Middleware\ModuleMiddleware;
use Clover\Annotation;
use Clover\Classes\Data\ArrayObject;
use Clover\Classes\HTTP\Request;
use Clover\Framework\Component\BaseController;

#[Annotation\Middleware(ModuleMiddleware::class)]
#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
    public function __construct(private ?Container $container = null)
    {
        parent::__construct($container);
    }

    #[Annotation\Route(method: 'GET', pattern: '/')]
    public function index(Request $request)
    {
        $this->addCssFileToHead('/App/Resource/fontawesome-free-6.5.2-web/css/all.css');
        $this->addCssFileToHead('/App/Resource/css/slicarousel.css');
        $this->addCssFileToHead('/App/Resource/layout.css');
        $this->addJsFileToHead('/App/Resource/js/jquery/jquery-3.3.1.min.js');
        $this->addJsFileToHead('/App/Frontend/dist/config.js');
        $this->addJsFileToHead('/App/Frontend/dist/variables.js');
        $this->addJsFileToHead('/App/Frontend/dist/coreJS.js');
        $this->addJsFileToHead('/App/Resource/layout.js');
        $this->addJsFileToHead('/App/Resource/js/slicarousel.js');

        return $this->render('/App/View/index.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/introduce/location')]
    public function introduce_location(Request $request)
    {
        return $this->render('/App/View/introduce/location.php');
    }
    
    #[Annotation\Route(method: 'GET', pattern: '/introduce/staff')]
    public function introduce_staff(Request $request)
    {
        return $this->render('/App/View/introduce/staff.php');
    }
}
