<?php

namespace App\Auth;

use \Exception;
use App\Models\User;
use Illuminate\Support\Arr;
use App\Exceptions\UserNotActiveException;
use App\Helpers\Cookie;

use Carbon\Carbon;

class Auth {

	public function user()
	{
		if($this->check()) {
			return User::find($_SESSION['user']);
		}

		return null;
		
	}

	public function check()
	{
		return isset($_SESSION['user']);
	}
	
	public function attempt($credentials)
	{	
		if(!$user = $this->retrieveByCredentials($credentials))
		{
			throw new Exception('No such user!');
		}

		if($user->activated != 1 ){
			throw new UserNotActiveException('error');
		}

 		// old version
		if(!password_verify($credentials['password'] , $user->password)) {
			throw new Exception('Could not login with those details');
		}

		if(array_key_exists('rememberme', $credentials )){
			$this->createRememberEnvironment($user);
		} 
		$_SESSION['user'] = $user->id;
		return true;
		 

	}
 
    public function retrieveByCredentials(array $credentials)
    {
    	if ($username = Arr::get( $credentials , 'username')) {
			
			return  User::where('username', $username)->first();

		}

		if ($email = Arr::get( $credentials  , 'email')) {

			return  User::where('email', $email)->first();

		}

		return false;
        
    }

    public function logout()
    {
    	unset($_SESSION['user']);
    }


    public function createRememberEnvironment(User $user)
    {
        $remember_identifier 	=  bin2hex(random_bytes(100)); 
        $remember_token 		=  password_hash( $user->email . $user->password , PASSWORD_DEFAULT) ;

        $user->remember_identifier = $remember_identifier;
        $user->remember_token = $remember_token;

        $user->save();
       
        Cookie::set(  env('AUTH_ID' ) , 
            "{$remember_identifier}.{$remember_token}",
            Carbon::now()->addWeek(2)->timestamp
        );

        
    }

}