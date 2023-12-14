<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP\Router;

use Xanax\Classes\Reflection\Handler as ReflectionHandler;

class Route
{
	private $segments;

    private $callback;

	private $pattern;

    private $arguments;

    public function __construct($pattern, $callback)
    {
        $this->pattern = $pattern; 
        $this->callback = $callback;
    }

    public function handle($container)
    {
        if (!is_callable($this->callback) && is_string(($this->callback)))
		{
			$static_method_arguments = explode('::', $this->callback);

			$class_name = $static_method_arguments[0];
			$method_name = $static_method_arguments[1];
		}

		if (class_exists($class_name))
		{
			$callback = new $class_name;
		}

		if (!isset($method_name))
		{
			return ReflectionHandler::Invoke($callback, $method_name, ($this->arguments ?? array()), []);
		}

		if (is_object($callback))
		{
			return ReflectionHandler::Invoke($callback, $method_name, ($this->arguments ?? array()), $container);
		}
    }

    public function match($segments)
    {
        if (empty($this->pattern))
        {
            return false;
        }

        $separated_segments = explode('/', trim($this->pattern ?? "", '/'));

        if (count($separated_segments) <= 0)
        {
            return false;
        }

        $parameters = [];
        $parameters['segments'] = [];
        $parameters['parameters'] = [];

        foreach ($separated_segments as $segment)
        {
            $parameters['segments'][] = $segment;

            if (preg_match('/^({\w*})$/', $segment))
            {
                $parameters['parameters'][] = $segment;
            }
        }

        for ($z = 0; $z < count($segments); $z++)
		{
            $segment = $segments[$z];

            $route_segment =  $parameters['segments'][$z];

			if (preg_match('/^({\w*})$/', $route_segment, $match))
			{
				$this->arguments[] = $segment;

				continue;
			}

			if ($route_segment != $segment)
			{
				return false;
			}
        }

        return true;
    }
}