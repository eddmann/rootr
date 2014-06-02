<?php namespace Rootr;


class ControllerTest extends \PHPUnit_Framework_TestCase
{

    public function testGetActionMethods()
    {
        $controller = new Fixtures\ProductController;

        $methods = callMethod($controller, 'getActionMethods', []);

        assertThat($methods, is(equalTo([ 'indexAction', 'showAction', 'deleteAction' ])));
    }

    public function testGetActionRoutes()
    {
        $controller = new Fixtures\ProductController;

        $routes = callMethod($controller, 'getActionRoutes', []);

        assertThat($routes, is(equalTo(
            [ [ '', 'indexAction' ], [ '/show', 'showAction' ], [ '/delete', 'deleteAction' ] ]
        )));
    }

    public function testCreateRouterFromController()
    {
        $controller = new Fixtures\ProductController;

        $router = $controller->getRouter();

        assertThat($router->getStaticRoutes(), is(equalTo(
            [ '' => [ 'GET' => [ 'Rootr\Fixtures\ProductController', 'indexAction' ] ] ]
        )));

        assertThat($router->getVariableRoutes(), is(equalTo(
            [
                '/(\d+)' => [
                    'GET' => [ [ 'Rootr\Fixtures\ProductController', 'showAction' ], [ 'id' ] ]
                ],
                '/delete/([^/]+)' => [
                    'DELETE' => [ [ 'Rootr\Fixtures\ProductController', 'deleteAction' ], [ 'id' ] ]
                ]
            ]
        )));
    }
}
