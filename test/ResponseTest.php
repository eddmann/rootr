<?php namespace Rootr;


class ResponseTest extends \PHPUnit_Framework_TestCase
{

    public function test200OKResponse()
    {
        $response = new Response(200);

        assertThat($this->readAttribute($response, 'status'), is(200));
    }

    public function test404NotFoundWithMessageResponse()
    {
        $response = new Response(404, 'Not Found');

        assertThat($this->readAttribute($response, 'status'), is(404));

        assertThat($this->readAttribute($response, 'body'), is('Not Found'));

        $rendered = getOutputBuffer(function () use ($response) {
            $response->render();
        });

        assertThat($rendered, is(equalTo('Not Found')));
    }

    public function test302RedirectWithLocation()
    {
        $response = new Response(302, '', [ 'Location' => 'url.com' ]);

        assertThat($this->readAttribute($response, 'status'), is(302));

        assertThat($this->readAttribute($response, 'body'), is(emptyString()));

        assertThat($this->readAttribute($response, 'headers'), is(equalTo([ 'Location' => 'url.com' ])));
    }

}
