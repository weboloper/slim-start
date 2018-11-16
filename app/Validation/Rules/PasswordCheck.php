<?php

namespace App\Validation\Rules;
use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class PasswordCheck extends AbstractRule
{
	protected $user_pass;

	public function __construct($user)
	{
	 	$this->user = $user;
	}

	public function validate($input)
	{	

		return  password_verify(  $input , $this->user->password  ); 

	}
}