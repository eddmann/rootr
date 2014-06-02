rootr
=====

_Routing URLs like a boss._

[![Build Status](https://travis-ci.org/eddmann/rootr.svg?branch=master)](https://travis-ci.org/eddmann/rootr)
[![Coverage Status](https://coveralls.io/repos/eddmann/rootr/badge.png?branch=master)](https://coveralls.io/r/eddmann/rootr?branch=master)

## Install

Get composer:

    wget http://getcomposer.org/composer.phar

Then add this to a `composer.json` in your project's root:

    {
        "require": {
            "eddmann/rootr": "*"
        }
    }

Now install:

    php composer.phar install

## Closure Example

```php
<?php

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

$response = $dispatcher->dispatch('GET', '/products/4');

$response->render(); // /products/4
```

## Controller Example

```php
<?php

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

$response = $dispatcher->dispatch('GET', '/products/show/2/cheese');

$response->render(); // /products/show/2/cheese
```

## Examples

You can run the examples using PHP's built-in web server by running the following:

    ./examples.sh

## Influenced By

- [FastRoute](https://github.com/nikic/FastRoute)
- [Pux](https://github.com/c9s/Pux)