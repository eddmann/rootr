<?php namespace Rootr;


class DispatcherTest extends \PHPUnit_Framework_TestCase
{

    protected $router;

    protected function setUp()
    {
        $this->router = \Mockery::mock('Rootr\Router');

        $this->router->shouldReceive('getStaticRoutes')->andReturn(
            [ '/' => [ 'GET' => function () {
                return '/';
            } ] ]
        );

        $this->router->shouldReceive('getVariableRoutes')->andReturn(
            [ '/(\d+)' => [ 'GET' => [ function ($id) {
                return "/$id";
            }, [ 'id' ] ] ] ]
        );
    }

    protected function tearDown()
    {
        \Mockery::close();
    }

    public function testDispatchStaticRoute()
    {
        $dispatcher = new Dispatcher($this->router);

        $response = $dispatcher->dispatch('GET', '/');

        assertThat($response, is(anInstanceOf('Rootr\Response')));
        assertThat($this->readAttribute($response, 'status'), is(equalTo(200)));
        assertThat($this->readAttribute($response, 'body'), is(equalTo('/')));
    }

    public function testDispatchVariableRoute()
    {
        $dispatcher = new Dispatcher($this->router);

        $response = $dispatcher->dispatch('GET', '/123');

        assertThat($response, is(anInstanceOf('Rootr\Response')));
        assertThat($this->readAttribute($response, 'status'), is(equalTo(200)));
        assertThat($this->readAttribute($response, 'body'), is(equalTo('/123')));
    }

    public function testDispatchInvalidVariableRoute()
    {
        $dispatcher = new Dispatcher($this->router);

        $response = $dispatcher->dispatch('GET', '/one');

        assertThat($response, is(anInstanceOf('Rootr\Response')));
        assertThat($this->readAttribute($response, 'status'), is(equalTo(404)));
        assertThat($this->readAttribute($response, 'body'), is(equalTo('Not Found')));
    }

    public function testDispatchNotFoundRoute()
    {
        $dispatcher = new Dispatcher($this->router);

        $response = $dispatcher->dispatch('GET', '/not-found');

        assertThat($response, is(anInstanceOf('Rootr\Response')));
        assertThat($this->readAttribute($response, 'status'), is(equalTo(404)));
        assertThat($this->readAttribute($response, 'body'), is(equalTo('Not Found')));
    }
}
