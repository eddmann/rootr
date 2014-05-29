<?php

function router(\Closure $init = null)
{
    static $router;

    if (! is_null($router)) {
        return $router;
    }

    $router = new \Rootr\Router;

    if (! is_null($init)) {
        $result = call_user_func($init, $router);

        if (! is_null($result)) {
            $router = $result;
        }
    }

    return $router;
}

function on($methods, $route, $handler)
{
    $router = router();

    foreach ((array) $methods as $method) {
        $router->add(strtoupper($method), $route, $handler);
    }
}

function dispatcher()
{
    $dispatcher = new \Rootr\Dispatcher(router());

    return function ($method, $uri) use ($dispatcher) {
        return $dispatcher->dispatch($method, $uri);
    };
}
