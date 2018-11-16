<?php

namespace App\Validation\Rules;
use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class UserLoginAvailable extends AbstractRule
{
	public function validate($input)
	{
		return User::where('username', $input)->count() === 0;
	}
}