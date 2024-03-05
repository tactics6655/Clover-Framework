<?php

namespace Clover\Implement;

use Clover\Implement\RequestInterface;

interface MiddlewareInterface
{
    public function handle(RequestInterface $request, callable $next);
}
