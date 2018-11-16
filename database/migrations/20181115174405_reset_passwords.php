<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ResetPasswords extends Migration
{
 
    public function up()
    {
    	$this->schema->create('reset_passwords', function (Blueprint $table) {
    			$table->increments('id');
                $table->unsignedInteger('user_id');
                $table->string('reset_token',100)->unique();
    			$table->integer('expires');
                $table->timestamps();
    	});

        
    	$this->schema->table('reset_passwords', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
    	});

    }

    public function down()
    {	
    	// $this->schema->table('', function (Blueprint $table) {

    	// });

    	$this->schema->drop('reset_passwords');
    }
}
