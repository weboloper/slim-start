<?php

// Middlewares
$app->add( new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add( new \App\Middleware\OldInputMiddleware($container));
$app->add( new \App\Middleware\CsrfViewMiddleware($container));
$app->add( new \App\Middleware\RememberMiddleware($container));



// csrf
$container['csrf']  = function($container) {
    return new \Slim\Csrf\Guard;
};
$app->add( $container->csrf );