<?php

declare(strict_types=1);

namespace Neko\Classes\Routing;

use Neko\Classes\DataStructor\Stack;
use Neko\Classes\Reflection\Handler as ReflectionHandler;

class StackableRequestHandler extends Stack
{
    public function addMiddlewares($middlewares, $container)
    {
        foreach ($middlewares as $middleware) {
            $next = parent::$stock->top();

            $closure = function () use ($middleware, $next, $container) {
                if (!empty($middleware) && is_string($middleware)) {
                    $instance = ReflectionHandler::getNewInstance($middleware);

                    return ReflectionHandler::invoke($instance, [$next], $container, 'handle');
                }

                return ReflectionHandler::callMethod($middleware, $next);
            };
            parent::$stock[] = $closure;
        }
    }
    
    /**
     * Call a top callback of last node
     */
    public function handle()
    {
        $top = parent::$stock->top();

        return $top();
    }

}