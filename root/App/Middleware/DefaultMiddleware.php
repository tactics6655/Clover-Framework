<?php

namespace App\Middleware;

use Neko\Framework\Component\Middleware;

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
