<?php namespace Rootr;


class Dispatcher
{

    protected $staticRoutes, $variableRoutes;

    public function __construct(array $routes)
    {
        list($this->staticRoutes, $this->variableRoutes) = $routes;
    }

    public function dispatch($method, $uri)
    {
        if ($response = $this->dispatchStaticRoute($method, $uri)) {
            return $response;
        }

        if ($response = $this->dispatchVariableRoute($method, $uri)) {
            return $response;
        }

        return new Response(404, 'Not Found');
    }

    protected function dispatchStaticRoute($method, $uri)
    {
        if (isset($this->staticRoutes[$uri][$method])) {
            $handler = $this->staticRoutes[$uri][$method];

            $response = $this->invokeHandler($handler);

            if (! $response instanceof Response) {
                $response = new Response(200, $response);
            }

            return $response;
        }

        return null;
    }

    protected function dispatchVariableRoute($method, $uri)
    {
        foreach ($this->variableRoutes as $pattern => $routes) {
            if (! preg_match($pattern, $uri, $matches)) {
                continue;
            }

            if (! isset($routes[$method])) {
                break;
            }

            list($handler, $variables) = $routes[$method];

            $arguments = array_combine($variables, array_slice($matches, 1));

            $response = $this->invokeHandler($handler, $arguments);

            if (! $response instanceof Response) {
                $response = new Response(200, $response);
            }

            return $response;
        }

        return null;
    }

    protected function invokeHandler($handler, array $arguments = [])
    {
        if (is_array($handler)) {
            list($class, $method) = $handler;

            return call_user_func_array([ new $class, $method ], $arguments);
        }

        return call_user_func_array($handler, $arguments);
    }

}
