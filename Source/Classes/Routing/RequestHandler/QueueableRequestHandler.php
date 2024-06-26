<?php

declare(strict_types=1);

namespace Clover\Classes\Routing;

use Clover\Classes\DataStructor\Queue;
use Clover\Classes\Reflection\Handler as ReflectionHandler;

class QueueableRequestHandler extends Queue
{
    public function addMiddlewares($middlewares, $container)
    {
        foreach ($middlewares as $middleware) {
            $next = parent::$stock->top();

            $closure = function () use ($middleware, $next, $container) {
                if (!empty($middleware) && is_string($middleware)) {
                    $instance = ReflectionHandler::getNewInstance($middleware);

                    return ReflectionHandler::invoke($instance, 'handle', [$next], $container);
                }

                return ReflectionHandler::callMethod($middleware, $next);
            };

            parent::$stock->enqueue($closure);
        }
    }

    public function handle()
    {
        $top = parent::$stock->top();

        return $top();
    }
}
