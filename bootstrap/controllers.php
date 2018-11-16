<?php

$container['Auth@AuthController'] 		= function($container) { return new \Modules\Auth\Controllers\AuthController($container); };
$container['Auth@PasswordController'] 	= function($container) { return new \Modules\Auth\Controllers\PasswordController($container); };

$container['Api@IndexController']		= function($container) { return new \Modules\Api\Controllers\IndexController($container); };
