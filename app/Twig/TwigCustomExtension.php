<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/slimphp/Twig-View
 * @copyright Copyright (c) 2011-2015 Josh Lockhart
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */
namespace App\Twig;

class TwigCustomExtension extends \Twig_Extension
{
    /**
     * @var \Slim\Interfaces\RouterInterface
     */
    private $router;

    /**
     * @var string|\Slim\Http\Uri
     */
    private $uri;

    public function __construct($router, $uri, $settings)
    {
        $this->router = $router;
        $this->uri = $uri;
        $this->settings = $settings;
    }

    public function getName()
    {
        return 'slim';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('assets', array($this, 'assets')),
  
        ];
    }

    public function assets($path)
    {   
        return $this->settings['app']['theme'].'/assets/' . $path;
    }

   
}
