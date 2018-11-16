<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Users extends Migration
{
 
    public function up()
    {
    	$this->schema->create('users', function (Blueprint $table) {
    			$table->increments('id');
                $table->string('username', 100 )->unique();
    			$table->string('email', 100 )->unique();
                $table->string('name', 100 )->nullable();
                $table->string('password');
                $table->string('token')->nullable();
                $table->string('remember_identifier')->nullable();
                $table->string('remember_token')->nullable();
                $table->tinyInteger('level')->default(0);
    			$table->tinyInteger('activated')->default(0);
    			$table->timestamps();
    	});

    	$this->schema->table('', function (Blueprint $table) {

    	});

    }

    public function down()
    {	
    	$this->schema->table('users', function (Blueprint $table) {

    	});

    	$this->schema->drop('users');
    }
}
