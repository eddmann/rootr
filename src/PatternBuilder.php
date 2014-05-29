<?php namespace Rootr;


class PatternBuilder
{

    protected function parseRouteVariables($route)
    {
        $pattern = '~\{\s*([\?a-zA-Z][a-zA-Z0-9_]*)\s*(?::\s*([^{}]*(?:\{(?-1)\}[^{}]*)*))?\}~x';

        preg_match_all($pattern, $route, $variables, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        return $variables;
    }

    protected function processRouteSegments($route)
    {
        $variables = $this->parseRouteVariables($route);

        if (! $variables) {
            return $route;
        }

        $position = 0;
        $segments = [];

        foreach ($variables as $variable) {
            $name = str_replace('?', '', $variable[1][0], $optional);
            $optional = (bool) $optional;
            $leading = '';

            if ($variable[0][1] > $position) {
                if ($optional) {
                    $leading = substr($route, $position, $variable[0][1] - $position);
                } else {
                    $segments[] = substr($route, $position, $variable[0][1] - $position);
                }
            }

            $processed = [ $name, isset($variable[2]) ? trim($variable[2][0]) : '[^/]+', $optional ];

            if ($leading) {
                $processed[] = $leading;
            }

            $segments[] = $processed;

            $position = $variable[0][1] + strlen($variable[0][0]);
        }

        if ($position != strlen($route)) {
            $segments[] = substr($route, $position);
        }

        return $segments;
    }

    public function build($route)
    {
        $segments = $this->processRouteSegments($route);

        if (is_string($segments)) {
            return $segments;
        }

        $pattern = '';
        $variables = [];

        foreach ($segments as $segment) {
            if (is_string($segment)) {
                $pattern .= preg_quote($segment, '~');
                continue;
            }

            list($varName, $varPattern, $optional) = $segment;

            $variables[] = $varName;

            if ($optional) {
                $pattern .= '(?:' . preg_quote($segment[3], '~') . '(' . $varPattern . '))?';
            } else {
                $pattern .= '(' . $varPattern . ')';
            }
        }

        return [ $pattern, $variables ];
    }
}
