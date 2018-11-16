<?php

namespace App\Middleware;
use App\Helpers\Cookie;
use App\Models\User;

class RememberMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {	
    	$auth_id = $this->container['settings']['app']['auth_id'];

        if(Cookie::exists($auth_id) && !$this->container->auth->check()) {
            $data = Cookie::get($auth_id);
            $credentials = explode('.', $data);
            if(empty(trim($data)) || count($credentials) !== 2) {
                Cookie::destroy($auth_id);
                return $response->withRedirect( $this->container->router->pathFor('auth.login') );
            } else {
                
                $remember_identifier = $credentials[0];
                $user = User::where('remember_identifier', $remember_identifier)->first();

                if($user) {

                	$remember_token =  $user->email . $user->password ; 

                    if( password_verify( $remember_token  , $user->remember_token) ) {
                        if($user->activated) {
                            $_SESSION['user'] = $user->id;
                            // We must define a reponse with a redirect to detect a session when we first hit the page, REQUEST_URI is optional.
                            $response = $response->withRedirect($_SERVER['REQUEST_URI']);
                            return $next($request, $response);
                        } else {
                            Cookie::destroy($auth_id);
                            
                            $user->remember_token = null;
                        	$user->remember_identifier = null;
                       		$user->save();

                            $this->container->flash->addMessage("danger", "Your account has not been activated.");
                            return $response->withRedirect( $this->container->router->pathFor('auth.login') );
                        }
                    } else {
                        $user->remember_token = null;
                        $user->remember_identifier = null;
                        $user->save();
                    }
                }
            }
        }
        
        $response = $next($request, $response);
        return $response;
    }
}