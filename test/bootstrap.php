<?php

require __DIR__ . '/../vendor/autoload.php';

require dirname((new ReflectionClass('Hamcrest\Matchers'))->getFileName()) . '.php';

function callMethod($object, $name, array $arguments)
{
    $class = new \ReflectionClass($object);
    $method = $class->getMethod($name);
    $method->setAccessible(true);
    return $method->invokeArgs($object, $arguments);
}

function getOutputBuffer(Closure $callback)
{
    ob_start();
    $callback();
    return ob_get_clean();
}