<?php

require '../vendor/autoload.php';

$router = new Rootr\Router;

class ProductController extends Rootr\Controller
{
    public function indexAction()
    {
        return '/products';
    }

    /**
     * @method GET
     * @route /{id:\d+}
     */
    public function displayByIdAction($id)
    {
        return "/products/$id";
    }

    public function showAction($id, $name = 'na')
    {
        return "/products/show/$id/$name";
    }
}

$router->get('/', function () {
    return '/';
});

$router->mountController('/products', 'ProductController');

$dispatcher = new Rootr\Dispatcher($router);

$method = $_SERVER['REQUEST_METHOD'];

$uri = '/' . trim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']), '/');

$response = $dispatcher->dispatch($method, $uri);

$response->render();