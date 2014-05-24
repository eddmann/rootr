<?php namespace Rootr;


class Router
{

    protected $staticRoutes, $variableRoutes;

    protected $patternBuilder;

    public function __construct(PatternBuilder $patternBuilder = null)
    {
        $this->patternBuilder = is_null($patternBuilder)
            ? new PatternBuilder
            : $patternBuilder;
    }

    public function add($method, $route, $handler)
    {
        $pattern = $this->patternBuilder->build($route);

        if (is_string($pattern)) {
            $this->addStaticRoute($method, $pattern, $handler);
        } else {
            $this->addVariableRoute($method, $pattern, $handler);
        }
    }

    protected function addStaticRoute($method, $pattern, $handler)
    {
        $this->staticRoutes[$pattern][$method] = $handler;
    }

    protected function addVariableRoute($method, $pattern, $handler)
    {
        list($regEx, $variables) = $pattern;

        $this->variableRoutes[$regEx][$method] = [ $handler, $variables ];
    }

    public function getStaticRoutes()
    {
        return $this->staticRoutes;
    }

    public function getVariableRoutes()
    {
        return $this->variableRoutes;
    }

}
