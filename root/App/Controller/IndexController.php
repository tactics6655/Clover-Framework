<?php

namespace App\Controller;

use Clover\Annotation;
use Clover\Framework\Component\BaseController;

#[Annotation\NotFound('IndexController::notFound')]
#[Annotation\Prefix('/')]
class IndexController extends BaseController
{
}
