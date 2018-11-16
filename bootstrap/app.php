<?php

// use Respect\Validation\Validator as v;

session_start();

require __DIR__ .'/../vendor/autoload.php';

$dotEnv = new Dotenv\Dotenv('../');
$dotEnv->load();

$settings = require __DIR__ .'/../bootstrap/settings.php';

$app = new \Slim\App($settings);

require __DIR__ .'/../bootstrap/dependencies.php';

require __DIR__ .'/../bootstrap/controllers.php';

require __DIR__ .'/../bootstrap/middlewares.php';

Respect\Validation\Validator::with('App\Validation\Rules');

require __DIR__ .'/../bootstrap/routes.php';
