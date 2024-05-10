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

    private function setResources()
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
    }

    #[Annotation\Route(method: 'GET', pattern: '/')]
    public function index(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/index.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/introduce/location')]
    public function introduce_location(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/introduce/location.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/introduce/staff')]
    public function introduce_staff(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/introduce/staff.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/introduce/none_benefit')]
    public function none_benefit(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/introduce/none_benefit.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/accident')]
    public function accident(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/accident.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/chuna')]
    public function chuna(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/chuna.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/cpm')]
    public function cpm(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/cpm.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/eswt')]
    public function eswt(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/eswt.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/exercise')]
    public function exercise(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/exercise.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/flu')]
    public function flu(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/flu.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/injection')]
    public function injection(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/injection.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/manual_therapy')]
    public function manual_therapy(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/manual_therapy.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/needle')]
    public function needle(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/needle.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/physical')]
    public function physical(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/physical.php');
    }

    #[Annotation\Route(method: 'GET', pattern: '/medical/postural_correction')]
    public function postural_correction(Request $request)
    {
        $this->setResources();

        return $this->render('/App/View/medical/postural_correction.php');
    }
}
