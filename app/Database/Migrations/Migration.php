<?php

namespace App\Database\Migrations;
use Illuminate\Database\Capsule\Manager as Capsule;


use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration {
    
    public function init()
    {
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver'    =>  'mysql',
            'host'      =>  getenv('DB_HOST','localhost'),
            'database'  =>  getenv('DB_DATABASE','localhost'),
            'username'  =>  getenv('DB_USERNAME','localhost'),
            'password'  =>  getenv('DB_PASSWORD','localhost'),
            'port'      =>  '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    =>  getenv('DB_PREFIX',''),
        
        ]);
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }

}