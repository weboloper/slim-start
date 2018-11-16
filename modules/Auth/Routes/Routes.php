<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/auth', 'Auth@AuthController:home')->setName('auth.home');
$app->get('/auth/logout', 'Auth@AuthController:getLogout')->setName('auth.logout');

$app->group('/auth', function () {
	
	$this->get('/register', 'Auth@AuthController:getRegister')->setName('auth.register');
	$this->post('/register', 'Auth@AuthController:postRegister');

	$this->get('/activate/{token}', 'Auth@AuthController:activateUser')->setName('auth.activateuser');

	$this->get('/activate', 'Auth@AuthController:getActivation')->setName('auth.activation');
	$this->post('/activate', 'Auth@AuthController:postActivation');

	$this->get('/login', 'Auth@AuthController:getLogin')->setName('auth.login');
	$this->post('/login', 'Auth@AuthController:postLogin');

	$this->get('/password/recover',  'Auth@PasswordController:getPasswordRecover')->setName('auth.password.recover');
	$this->post('/password/email', 'Auth@PasswordController:postPasswordEmail')->setName('auth.password.email');

	$this->get('/password/reset',  'Auth@PasswordController:getPasswordReset')->setName('auth.password.reset');
	$this->post('/password/reset',  'Auth@PasswordController:postPasswordReset');

	// $this->get('', [ App\Modules\Auth\Controllers\IndexController::class , 'index' ] )->setName('homePage'); // this not works

	// $this->get('/home', function($request, $response ) {
	// 	return $this->view->render($response, '@auth\auth\home.twig'); // this is working
	// });

})->add( new GuestMiddleware($container));

$app->group('/auth', function () {
	$this->get('/password/change', 'Auth@PasswordController:getChangePassword')->setName('auth.password.change');
	$this->post('/password/change', 'Auth@PasswordController:postChangePassword');
})->add( new AuthMiddleware($container));

// $app->get('/home', function($request, $response) {
// 	return 'Home';
// });