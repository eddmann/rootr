<?php namespace Rootr;


class PatternBuilderTest extends \PHPUnit_Framework_TestCase
{

    protected $instance;

    public function setUp()
    {
        $this->instance = new PatternBuilder;
    }

    public function testEmptyRoute()
    {
        $pattern = $this->instance->build('/');

        assertThat($pattern, is(equalTo('/')));
    }

    public function testRouteWithVariable()
    {
        $pattern = $this->instance->build('/news/{year}');

        assertThat($pattern, is(arrayWithSize(2)));

        list($regEx, $variables) = $pattern;

        assertThat($regEx, is(equalTo('/news/([^/]+)')));

        assertThat($variables, is(equalTo([ 'year' ])));
    }

    public function testRouteWithVariableAndPattern()
    {
        $pattern = $this->instance->build('/news/{year:\d{4}}');

        assertThat($pattern, is(arrayWithSize(2)));

        list($regEx, $variables) = $pattern;

        assertThat($regEx, is(equalTo('/news/(\d{4})')));

        assertThat($variables, is(equalTo([ 'year' ])));

        assertThat(preg_match('~^' . $regEx. '$~', '/news/2014'), is(true));

        assertThat(preg_match('~^' . $regEx. '$~', '/news/two-thousand-and-fourteen'), is(false));
    }

    public function testRouteWithMultipleVariables()
    {
        $pattern = $this->instance->build('/news/{year:\d{4}}/{month:\d{2}}/{slug}');

        assertThat($pattern, is(arrayWithSize(2)));

        list($regEx, $variables) = $pattern;

        assertThat($regEx, is(equalTo('/news/(\d{4})/(\d{2})/([^/]+)')));

        assertThat($variables, is(equalTo([ 'year', 'month', 'slug' ])));

        assertThat(preg_match('~^' . $regEx . '$~', '/news/2014/05/lastest-news'), is(true));

        assertThat(preg_match('~^' . $regEx . '$~', '/news/2014/05'), is(false));
    }

    public function testRouteWithOptionalVariable()
    {
        $pattern = $this->instance->build('/news/{year:\d{4}}/{month:\d{2}}/{?slug}');

        assertThat($pattern, is(arrayWithSize(2)));

        list($regEx, $variables) = $pattern;

        assertThat($regEx, is(equalTo('/news/(\d{4})/(\d{2})(?:/([^/]+))?')));

        assertThat($variables, is(equalTo([ 'year', 'month', 'slug' ])));

        assertThat(preg_match('~^' . $regEx . '$~', '/news/2014/05/lastest-news'), is(true));

        assertThat(preg_match('~^' . $regEx . '$~', '/news/2014/05'), is(true));
    }

}
