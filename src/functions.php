<?php namespace Rootr;

function router(\Closure $callback, array $options = [])
{
    $options += [
        'cache' => false,
        'cacheFile' => './routes.cache',
    ];

    if ($options['cache'] && file_exists($options['cacheFile'])) {
        $router = unserialize(file_get_contents($options['cacheFile']));
        echo 'cache';
    } else {
        echo 'no cache';
        $router = new Router;

        $response = $callback($router);

        if (! is_null($response)) {
            $router = $response;
        }

        if ($options['cache']) {
            file_put_contents($options['cacheFile'], serialize($router));
        }
    }

    $dispatcher = new Dispatcher($router);

    return function ($method, $uri) use ($dispatcher) {
        return $dispatcher->dispatch($method, $uri);
    };
}
