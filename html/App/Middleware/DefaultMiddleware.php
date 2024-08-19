<?php

namespace App\Middleware;

use Clover\Framework\Component\Middleware;

class ModuleMiddleware
{
    public function __construct()
    {
    }

    public function handle($next)
    {
        return $next();
    }
}
