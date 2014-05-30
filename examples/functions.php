<?php

require '../vendor/autoload.php';

$dispatch = Rootr\router(function (Rootr\Router $router) {

    $router->get('/', function () {
        return '/';
    });

    $router->get('/products', function () {
        return '/products';
    });

    $router->get('/products/{id:\d+}', function ($id) {
        return "/products/$id";
    });

}, [
    'cache' => false
]);

$method = $_SERVER['REQUEST_METHOD'];

$uri = '/' . trim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']), '/');

$dispatch($method, $uri)->render();