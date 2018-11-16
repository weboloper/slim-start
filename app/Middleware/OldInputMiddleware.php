<?php

namespace App\Middleware;

class OldInputMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{	
		$values = isset($_SESSION['old']) ? $_SESSION['old'] : null;
		$this->container->view->getEnvironment()->addGlobal('old', $values );
		$_SESSION['old'] = $request->getParams();

		$response = $next($request, $response );
		return $response;
	}
}