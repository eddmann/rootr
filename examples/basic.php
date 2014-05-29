<?php

require '../vendor/autoload.php';

$router = new Rootr\Router;

$router->get('/', function () {
   return '/';
});

$router->get('/products', function () {
    return '/products';
});

$router->get('/products/{id:\d+}', function ($id) {
   return "/products/$id";
});

$router->get('/products/show/{id:\d+}/{?name}', function ($id, $name = 'na') {
    return "/products/show/$id/$name";
});

$router->get('/product.json', function () {
    $product = json_encode([ 'name' => 'Cheese', 'value' => 12.55 ]);

    return (new Rootr\Response(200, $product))->asJson();
});

$dispatcher = new Rootr\Dispatcher($router);

$method = $_SERVER['REQUEST_METHOD'];

$uri = '/' . trim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']), '/');

$response = $dispatcher->dispatch($method, $uri);

$response->render();