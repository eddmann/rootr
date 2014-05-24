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

    public function mountController($baseRoute, $controller)
    {
        if (is_string($controller)) {
            $controller = new $controller;
        }

        $router = $controller->getRouter();

        foreach ($router->getStaticRoutes() as $route => $methods) {
            foreach ($methods as $method => $handler) {
                $this->addStaticRoute($method, $baseRoute . $route, $handler);
            }
        }

        $baseRoute = preg_quote($baseRoute, '~');

        foreach ($router->getVariableRoutes() as $pattern => $methods) {
            foreach ($methods as $method => $data) {
                list($handler, $variables) = $data;

                $this->addVariableRoute($method, [ $baseRoute . $pattern, $variables ], $handler);
            }
        }
    }

    public function __call($name, array $arguments)
    {
        $name = strtoupper($name);

        call_user_func_array([ $this, 'add' ], array_merge([ $name ], $arguments));
    }

}
