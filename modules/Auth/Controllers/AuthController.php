<?php

namespace Modules\Auth\Controllers;

use \Exception;
use App\Models\User;
use Respect\Validation\Validator as v;
use App\Exceptions\UserNotActiveException;

class AuthController extends Controller
{	
	public function home($request, $response) {
		return $this->view->render($response, '@Auth\auth\home.twig');
	}

	public function getRegister($request, $response)
	{
		return $this->view->render($response, '@Auth\auth\register.twig');
	}

	public function postRegister($request, $response)
	{	
		$validation = $this->validator->validate($request,[
			'firstname'			=>	v::noWhiteSpace()->notEmpty()->setName('Firstname'),
			'lastname'			=>	v::noWhiteSpace()->notEmpty()->setName('Lastname'),

			'username'			=>	v::noWhiteSpace()->notEmpty()->alnum()->userLoginAvailable()->setName('Username'),
			'email'				=>	v::noWhiteSpace()->notEmpty()->email()->emailAvailable()->setName('E-mail'),
			'password'			=>	v::noWhiteSpace()->notEmpty()->length(6, 50)->setName('Password'),
			'password_again'	=>	v::noWhiteSpace()->notEmpty()->length(6, 50)->setName('Password')->passwordMatch( $request->getParam('password')),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.register'));
		}

		$token = bin2hex(random_bytes(100));

		$user = User::create([
			'username' 	=> $request->getParam('username'),
			'name' 		=> $request->getParam('firstname') . ' ' . $request->getParam('lastname') ,
			'email'		=> $request->getParam('email'),
			'password'	=> password_hash($request->getParam('password'), PASSWORD_DEFAULT),
			'token'		=> $token
		]);

		$app = $this->container['settings']['app'];

		$url = $app['url'].$this->router->pathFor('auth.activateuser', ['token' => $token ]);

		$sendEmail = $this->mail->send('@Auth/email/registered.twig', ['url' => $url , 'app' => $app ] , function($message) use ($user) {
			$message->to(  $user->email  );
			$message->subject('You are registered');
		}); 

		if(! $sendEmail ) {
			$this->flash->addMessage('danger',  'Unexpected error!' );
			return $response->withRedirect($this->router->pathFor('auth.register'));
		}
	
		// auto-login on sign up
		// $this->auth->attempt($user->email, $request->getParam('password'))
		$this->flash->addMessage('info', 'Activation email has been sent to your e-mail. You need activation to login!');
		return $response->withRedirect($this->router->pathFor('auth.login'));
	}

	public function getLogin($request, $response)
	{	
		// die(var_dump( $request->getServerParams()['HTTP_USER_AGENT'] ));
		return $this->view->render($response, '@Auth\auth\login.twig');
	}

	public function postLogin($request, $response)
	{	
		$validation = $this->validator->validate($request,[
			// 'username'	=>	v::noWhiteSpace()->notEmpty()->alnum()->userLoginAvailable()->setName('Username'),
			'email'		=>	v::noWhiteSpace()->notEmpty()->email()->setName('E-mail'),
			'password'	=>	v::noWhiteSpace()->notEmpty()->length(6, 50)->setName('Password'),
		]);

		if ($validation->failed()) {
			$this->flash->addMessage('danger', 'Validation failed!');
			return $response->withRedirect($this->router->pathFor('auth.login'));
		}

		try {
			// $this->auth->attempt($request);
			$this->auth->attempt($request->getParams());
		} catch (UserNotActiveException $e) {

			$url = $this->container->router->pathFor('auth.activation');
			$message = 'Your account is not activated yet. Before you can login, you must active your account with the code sent to your email address. <br/>If you did not receive this email, please check your junk/spam folder. Click <a href="' .$url . '">here</a> to resend the activation email.';

			$this->flash->addMessage('raw', ['info',  $message ]);
 			return $response->withRedirect($this->router->pathFor('auth.login'));
		} catch (Exception $e) {
			$this->flash->addMessage('danger', $e->getMessage());
 			return $response->withRedirect($this->router->pathFor('auth.login'));
		}

		// $auth = $this->auth->attempt($request->getParams());

 	// 	if( !$auth ) {
 	// 		$this->flash->addMessage('danger', 'Could not login with those details');
 	// 		return $response->withRedirect($this->router->pathFor('auth.login'));
 	// 	}
 		
 		return $response->withRedirect($this->router->pathFor('auth.home'));
		
 	}

 	public function getLogout($request, $response)
	{	
		$this->auth->logout();
		return $response->withRedirect($this->router->pathFor('auth.home'));
	}

 	public function activateUser($request, $response)
	{
		$token = $request->getAttribute('token');

		if( $user = User::where('token' , $token)->where('activated' , false )->first() )
		{
			$user->activated = true;
			$user->token = null;
			$user->save();

			$this->flash->addMessage('info', 'Your account has been activated.');
			return $response->withRedirect($this->router->pathFor('auth.login'));
		}

		return $this->view->render($response, 'errors/404.twig');
		
	}

	public function getActivation($request, $response)
	{	
 		return $this->view->render($response, '@Auth\auth\activation.twig');
	}

	public function postActivation($request, $response)
	{
		$email 		= $request->getParam('email');

		$validation = $this->validator->validate($request,[
			'email'				=>	v::noWhiteSpace()->notEmpty()->email(),
		]);

		if ($validation->failed()) {
			$this->flash->addMessage('danger', 'Validation failed!');
			return $response->withRedirect($this->router->pathFor('auth.login'));
		}

		if( !$user = $this->auth->retrieveByCredentials($request->getParams()) ) {
			$this->flash->addMessage('danger', 'Entity not found!');
			return $response->withRedirect($this->router->pathFor('auth.login'));
		}

		if($user->activated === 0 ) {

			$token = bin2hex(random_bytes(100));
			$user->token = $token;
			$user->save();

			$app = $this->container['settings']['app'];
			$url = $app['url'].$this->router->pathFor('auth.activateuser', ['token' => $token ]);

			$sendEmail = $this->mail->send('@Auth/email/registered.twig', ['url' => $url , 'app' => $app ] , function($message) use ($user) {
				$message->to(  $user->email  );
				$message->subject('You are registered');
			}); 

			if(! $sendEmail ) {
				$this->flash->addMessage('danger',  'Unexpected error!' );
				return $response->withRedirect($this->router->pathFor('auth.register'));
			}

			$this->flash->addMessage('info', 'Activation email has been sent to your e-mail. You need activation to login!');
			return $response->withRedirect($this->router->pathFor('auth.login'));

		}
	 
		$this->flash->addMessage('danger', 'Unexpected error!');
		return $response->withRedirect($this->router->pathFor('auth.login'));

	}





	public function test_mail()
	{
		try {
		    //Server settings
		    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'HOST';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = 'USERNAME';                 // SMTP username
		    $mail->Password = 'PASSWORD';                           // SMTP password
		    $mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = 26;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('from@example.com', 'Mailer');
		    $mail->addAddress('test@gmail.com', 'Joe User');     // Add a recipient
 

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = 'Here is the subject';
		    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		    $mail->send();
		    
		} catch (Exception $e) {
		    die(var_dump( 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo ));
		}
		try {
			$this->mail->send('@Auth/email/registered.twig', ['url' => 'google.com'] , function($message)      {
				$message->to( "test@gmail.com" );
				$message->subject('You are registered');
			}); 
			// die(var_dump(88));
		} catch (Exception $e) {
		    die(var_dump($e->getMessage()));
		}
	}


	
}