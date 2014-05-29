<?php namespace Rootr;


class Controller
{

    protected function getActionMethods()
    {
        $self = new \ReflectionObject($this);

        return array_map(function ($method) {
            return $method->getName();
        }, array_filter($self->getMethods(), function ($method) {
            return preg_match('/Action$/', $method->getName());
        }));
    }

    protected function getActionRoutes()
    {
        return array_map(function ($method) {
            $name = preg_replace('/Action$/', '', $method);

            if ($name == 'index') {
                $route = '';
            } else {
                $route = '/' . preg_replace_callback('/[A-Z]/', function ($matches) {
                    return '-' . strtolower($matches[0]);
                }, $name);
            }

            return [ $route, $method ];
        }, $this->getActionMethods());
    }

    public function getRouter()
    {
        $router = new Router;

        foreach ($this->getActionRoutes() as $methodRoute) {
            list($route, $methodName) = $methodRoute;

            $method = new \ReflectionMethod($this, $methodName);
            $httpMethod = 'GET';
            $hasRoute = false;

            if ($comment = $method->getDocComment()) {
                if (preg_match('~^[\s*]*\@method\s([^\s]+)~im', $comment, $match)) {
                    $httpMethod = trim(strtoupper(array_pop($match)));
                }
                if (preg_match('~^[\s*]*\@route\s([^\s]+)~im', $comment, $match)) {
                    $route = trim(array_pop($match));
                    $hasRoute = true;
                }
            }

            if (! $hasRoute) {
                foreach ($method->getParameters() as $parameter) {
                    $route .= '/{' . ($parameter->isOptional() ? '?' : '') . $parameter->getName() . '}';
                }
            }

            $router->add($httpMethod, $route, [ get_class($this), $methodName ]);
        }

        return $router;
    }
}
