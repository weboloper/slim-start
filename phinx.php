<?php

$dotEnv = new Dotenv\Dotenv('./');
$dotEnv->load();

require './bootstrap/settings.php';
 
$config = $container['settings']['db'];

return [
    'paths'                => [
        'migrations' => 'database/migrations',
        'seeds'      => 'database/seeds',
    ],
    'migration_base_class' => 'App\Database\Migrations\Migration',
    'templates'            => [
        'file' => 'App\Database\Migrations\MigrationStub.php',
    ],
    'default_database' => 'default',
    'environments' => [
        'default_migration_table' => 'migrations',
        'default'  => [
            'adapter'   =>  'mysql',
            'host'      =>  getenv('DB_HOST','localhost'),
            'name'      =>  getenv('DB_DATABASE','your_db'),
            'user'      =>  getenv('DB_USERNAME', 'root'),
            'pass'      =>  getenv('DB_PASSWORD',''),
            'port'      =>  '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    =>  getenv('DB_PREFIX', ''),
        ],
    ],
];