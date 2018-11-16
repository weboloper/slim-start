<?php

$app->get('/', 'Auth@AuthController:home')->setName('auth.home');


// $app->group('', function () {
//     $this->get('/', function($request, $response) {
//     	return '';
//     	 // return $this->view->render($response, '@main\index.twig');
// 	});
// });

// $app->get('/home', function($request, $response) {
// 	return 'Home';
// });