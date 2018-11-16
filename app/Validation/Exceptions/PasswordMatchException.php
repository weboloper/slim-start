<?php

namespace App\Validation\Exceptions;
use Respect\Validation\Exceptions\ValidationException;


class PasswordMatchException extends ValidationException
{
	public static $defaultTemplates	= [
		self::MODE_DEFAULT	=> [
			self::STANDARD => '{{name}} not match' ,
		],
	];
}