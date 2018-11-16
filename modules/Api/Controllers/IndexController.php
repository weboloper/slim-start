<?php

namespace Modules\Api\Controllers;
use Corcel\Model\Post;

class IndexController extends Controller
{
 	public function index($request, $response) {
 		
		$posts = Post::all();

		$post = Post::find(9);
 		$data['data'] = $posts;
        return $response->withJSON($data,200,JSON_PRETTY_PRINT);
	}
	
}