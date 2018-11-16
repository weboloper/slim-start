<?php
 
namespace App\Exceptions;
 
use Exception;
 
class UserNotActiveException extends Exception
{

    public function report()
    {
        // \Log::debug('User not found');
    }
}