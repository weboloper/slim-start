<?php
return [
	'settings' => [
		'displayErrorDetails' => (getenv('APP_ENV')=='development')? true : false,

        'app' => [
            // App variables
            'name'      => getenv('APP_NAME','Slim Auth'),
            'desc'      => getenv('APP_DESC','Slim php startup project with Authentication, Twig, Flash and PHPMailer'),
            'url'       => getenv('APP_URL','http://slimauth.dev'),  // base_url() already works in views
            'auth_id'   => getenv('AUTH_ID','user_r'),
            'theme'     => getenv('APP_THEME','default'),
        ],

        'db' => [
    		// Database settings
    		'driver'    => 'mysql',
    		'host' 		=>	getenv('DB_HOST','localhost'),
            'database' 	=>	getenv('DB_DATABASE','your_db'),
            'username' 	=>	getenv('DB_USERNAME', 'root'),
            'password' 	=>	getenv('DB_PASSWORD',''),
    		'charset'   => 'utf8',
    		'collation' => 'utf8_unicode_ci',
    		'prefix'    => getenv('DB_PREFIX', 'wp_'), // Specify the prefix for WordPress tables, default prefix is 'wp_'
        ],

		
        'logger' => [
            // Monolog settings
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

	]
];