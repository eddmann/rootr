<?php

require __DIR__ . '/../vendor/autoload.php';

require dirname((new ReflectionClass('Hamcrest\Matchers'))->getFileName()) . '.php';

require 'helpers.php';

require 'fixtures/ProductController.php';
