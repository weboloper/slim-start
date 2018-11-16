<?php

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule) {
    return $capsule;
};

// App information
// $container['app'] = function ($container) {
//     return [
//         'app_name'  => getenv('APP_NAME','Slim Auth'),
//         'app_desc'  => getenv('APP_DESC','Slim php startup project with Authentication, Twig, Flash and PHPMailer'),
//         'app_url'   => getenv('APP_URL','http://slimauth.dev')  // base_url() already works in views
//     ];
// };

// Auth
$container['auth']  = function($container) {
    return new \App\Auth\Auth;
};

// flash
$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

// views
$container['view'] = function($container) {

	$view = new \Slim\Views\Twig(__DIR__. '/../resources/views/' . getenv('APP_THEME','default') , [
		'cache' => false,
        'debug' => true,
	]);

	$modulePath = __DIR__.'/../modules/';
	// $modulePath = __DIR__.'/../app/modules/';
	//Load View Module Wise
    $_directories = glob($modulePath . "*");

    foreach ($_directories as $dir) {
        $_viewPath = $dir . '/Views/';
        if (is_dir($_viewPath)) {
            $view->getLoader()->addPath($_viewPath, str_replace($modulePath, '', $dir));
        }
        // Module Path
        $moduleDirs[] = $dir;
    }

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));
    $view->addExtension(new Twig_Extension_Debug());

    $view->getEnvironment()->addGlobal('container', [
        'request'   => $container->request
    ]);

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user'  => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('app', $container['settings']['app'] );

    $view->getEnvironment()->addGlobal('flash', $container->flash);

	return $view;
};

// monolog
$container['logger'] = function ($app) {
    $settings = $app->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Mail /Phpmailer
$container['mail'] = function ($container) { 

    $mailer = new PHPMailer\PHPMailer\PHPMailer;

    $mailer->isSMTP();
    $mailer->SMTPDebug = 0;
    $mailer->Host       = getenv('MAIL_HOST','smtp.gmail.com');
    $mailer->SMTPAuth   = getenv('MAIL_AUTH', true );
    $mailer->SMTPSecure = getenv('MAIL_SECURE','ssl');
    $mailer->Port       = getenv('MAIL_PORT','465');
    $mailer->Username   = getenv('MAIL_USERNAME','username@gmail.com');
    $mailer->Password   = getenv('MAIL_PASSWORD','password');
    $mailer->isHTML();
    $mailer->CharSet  = 'UTF-8';
    $mailer->setFrom( getenv('MAIL_SENDER','noreply@gmail.com') );

    return new \App\Mail\Mail($container->view, $mailer );
 
};

// Validaton
$container['validator'] = function($app) {
    return new App\Validation\Validator;
};

 