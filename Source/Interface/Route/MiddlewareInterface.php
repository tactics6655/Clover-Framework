<?php

namespace Neko\Implement;

use Neko\Implement\RequestInterface;

interface MiddlewareInterface
{
    public function handle(RequestInterface $request, callable $next);
}
