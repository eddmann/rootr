<?php

require 'vendor/autoload.php';

$router = new Rootr\Router;

$router->get('/', function () {
    return 'Index Page.';
});

$router->get('/products', function () {
    return 'Product Listings.';
});

$router->get('/products/{id:\d+}', function ($id) {
   return "Details for Product $id";
});

$router->add('GET', '/products/first', function () {
    return new Rootr\Response(302, '', [ 'Location' => '/products/1' ]);
});

$dispatcher = new Rootr\Dispatcher($router);

$method = $_SERVER['REQUEST_METHOD'];

$uri = '/' . trim($_SERVER['REQUEST_URI'], '/');

$response = $dispatcher->dispatch($method, $uri);

$response->render();