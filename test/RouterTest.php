<?php namespace Rootr;


class RouterTest extends \PHPUnit_Framework_TestCase
{

    protected function tearDown()
    {
        \Mockery::close();
    }

    public function testCreateWithPatternBuilder()
    {
        $patternBuilder = \Mockery::mock('Rootr\PatternBuilder');

        $router = new Router($patternBuilder);

        assertThat($router, is(anInstanceOf('Rootr\Router')));
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateWithInvalidPatternBuilder()
    {
        $invalidPatternBuilder = \Mockery::mock('Rootr\InvalidPatternBuilder');

        $router = new Router($invalidPatternBuilder);
    }

    public function testAddStaticRoute()
    {
        $patternBuilder = \Mockery::mock('Rootr\PatternBuilder');
        $patternBuilder->shouldReceive('build')->andReturn('/products');

        $router = new Router($patternBuilder);
        $router->add('GET', '/products', function () { return '/products'; });

        assertThat($router->getStaticRoutes(), arrayWithSize(1));
        assertThat($router->getStaticRoutes(), hasKeyInArray('/products'));
        assertThat($router->getVariableRoutes(), emptyArray());
    }

    public function testAddVariableRoute()
    {
        $patternBuilder = \Mockery::mock('Rootr\PatternBuilder');
        $patternBuilder->shouldReceive('build')->andReturn([ '/products/([^/]+)', [ 'id' ]]);

        $router = new Router($patternBuilder);
        $router->add('GET', '/products/{id}', function ($id) { return "/products/$id"; });

        assertThat($router->getStaticRoutes(), emptyArray());
        assertThat($router->getVariableRoutes(), arrayWithSize(1));
        assertThat($router->getVariableRoutes(), hasKeyInArray('/products/([^/]+)'));
    }

    public function testAddVariableWithRegexRoute()
    {
        $patternBuilder = \Mockery::mock('Rootr\PatternBuilder');
        $patternBuilder->shouldReceive('build')->andReturn([ '/products/(\d+)', [ 'id' ]]);

        $router = new Router($patternBuilder);
        $router->add('GET', '/products/{id}', function ($id) { return "/products/$id"; });

        assertThat($router->getStaticRoutes(), emptyArray());
        assertThat($router->getVariableRoutes(), arrayWithSize(1));
        assertThat($router->getVariableRoutes(), hasKeyInArray('/products/(\d+)'));
    }

    public function testMountControllerWithBaseRoute()
    {
        $patternBuilder = \Mockery::mock('Rootr\PatternBuilder');

        $router = new Router($patternBuilder);

        $controllerRouter = \Mockery::mock('Rootr\Router');
        $controllerRouter->shouldReceive('getStaticRoutes')->andReturn(
            [ '' => [ 'GET' => [ 'Rootr\ProductController', 'indexAction' ] ] ]);
        $controllerRouter->shouldReceive('getVariableRoutes')->andReturn(
            [ '/(\d+)' => [ 'GET' => [ [ 'Rootr\ProductController', 'showAction' ], [ 'id' ] ] ] ]);

        $controller = \Mockery::mock([ 'getRouter' => $controllerRouter ]);

        $router->mountController('/products', $controller);

        assertThat($router->getStaticRoutes(), arrayWithSize(1));
        assertThat($router->getStaticRoutes(), hasKeyInArray('/products'));
        assertThat($router->getVariableRoutes(), arrayWithSize(1));
        assertThat($router->getVariableRoutes(), hasKeyInArray('/products/(\d+)'));
    }
}
