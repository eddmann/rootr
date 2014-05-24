<?php

require 'vendor/autoload.php';

$router = new Rootr\Router;

$router->add('GET', '/products', function () {
    return 'Product Listings.';
});

$router->add('GET', '/products/{id}', function ($id) {
   return "Details for Product $id";
});

$dispatcher = new Rootr\Dispatcher($router);

$method = $_SERVER['REQUEST_METHOD'];

$uri = '/' . trim($_SERVER['REQUEST_URI'], '/');

$response = $dispatcher->dispatch($method, $uri);

$response->render();