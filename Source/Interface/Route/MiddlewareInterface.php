<?php

namespace Xanax\Implement;

use Xanax\Implement\RequestInterface;

interface MiddlewareInterface
{
    public function handle(RequestInterface $request,callable $next);
}