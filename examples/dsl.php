<?php

require '../vendor/autoload.php';

require Rootr\Router::getDSLPath();

router(function (\Rootr\Router $router) {
    $router->get('/', function () {
        return '/';
    });
});

on('get', '/products', function () {
    return '/products';
});

on('get', '/products/{id:\d+}', function ($id) {
    return "/products/$id";
});

on('get', '/products/show/{id:\d+}/{?name}', function ($id, $name = 'na') {
    return "/products/show/$id/$name";
});

on('get', '/product.json', function () {
    $product = json_encode([ 'name' => 'Cheese', 'value' => 12.55 ]);

    return (new Rootr\Response(200, $product))->asJson();
});

$dispatcher = dispatcher();

$method = $_SERVER['REQUEST_METHOD'];

$uri = '/' . trim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']), '/');

$response = $dispatcher($method, $uri);

$response->render();