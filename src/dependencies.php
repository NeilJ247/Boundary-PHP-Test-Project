<?php
// DIC configuration

$container = $app->getContainer();

$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();

    return $capsule;
};

/*
 *  Give each of the controller classes a reference the same instance of our database connection from the
 *  container interface
*/
$container[\BoundaryWS\Resolver\ProductCreateRequestResolver::class] = function ($container) {
    return new \BoundaryWS\Resolver\ProductCreateRequestResolver();
};
$container[\BoundaryWS\Resolver\PurchasesResponseResolver::class] = function ($container) {
    return new \BoundaryWS\Resolver\PurchasesResponseResolver();
};
$container[\BoundaryWS\Service\ProductService::class] = function ($container) {
    return new \BoundaryWS\Service\ProductService($container['db']->getConnection());
};
$container[\BoundaryWS\Service\PurchaseService::class] = function ($container) {
    return new \BoundaryWS\Service\PurchaseService($container['db']->getConnection());
};
$container[\BoundaryWS\Controller\AuthController::class] = function ($container) {
    return new \BoundaryWS\Controller\AuthController($container['db']->getConnection());
};
$container[\BoundaryWS\Controller\UserController::class] = function ($container) {
    return new \BoundaryWS\Controller\UserController($container['db']->getConnection());
};
$container[\BoundaryWS\Controller\ProductController::class] = function ($container) {
    return new \BoundaryWS\Controller\ProductController(
        $container[\BoundaryWS\Service\ProductService::class],
        $container[\BoundaryWS\Resolver\ProductCreateRequestResolver::class]
    );
};
$container[\BoundaryWS\Controller\PurchaseController::class] = function ($container) {
    return new \BoundaryWS\Controller\PurchaseController(
        $container[\BoundaryWS\Service\PurchaseService::class],
        $container[\BoundaryWS\Resolver\PurchasesResponseResolver::class]
    );
};
