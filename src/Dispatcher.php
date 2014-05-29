<?php namespace Rootr;


class Dispatcher
{

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
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
        $routes = $this->router->getStaticRoutes();

        if (isset($routes[$uri][$method])) {
            $handler = $routes[$uri][$method];

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
        $routes = $this->router->getVariableRoutes();

        foreach ($routes as $pattern => $methods) {
            if (! preg_match('~^' . $pattern . '$~', $uri, $matches)) {
                continue;
            }

            if (! isset($methods[$method])) {
                break;
            }

            list($handler, $variables) = $methods[$method];

            $response = $this->invokeHandler($handler, array_slice($matches, 1));

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
