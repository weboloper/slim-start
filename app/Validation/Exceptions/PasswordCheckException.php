<?php

namespace App\Validation\Exceptions;
use Respect\Validation\Exceptions\ValidationException;


class PasswordCheckException extends ValidationException
{
	public static $defaultTemplates	= [
		self::MODE_DEFAULT	=> [
			self::STANDARD => '{{name}} is not valid' ,
		],
	];
}