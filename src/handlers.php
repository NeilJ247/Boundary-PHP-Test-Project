<?php

$container = $app->getContainer();
$container['errorHandler'] = function ($container) {
    return new \BoundaryWS\Handler\ApiExceptionHandler();
};