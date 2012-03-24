<?php

/*
 * Twig Extension
 * Created on 11/03/12 at 16:30
 */

namespace PHPPie\MVC\View\Twig;

class Extension extends \Twig_Extension {
	public function __construct()
	{
		
	}
	
	public function getFunctions()
    {
        return array(
            'render' => new \Twig_Function_Method($this, 'functionRender', array('is_safe' => array('html'))),
            'link' => new \Twig_Function_Method($this, 'functionLink'),
        );
    }
    
    public function getGlobals()
    {
		$kernel = \PHPPie\Core\StaticContainer::getService('kernel');
		
        return array(
			'kernel' => $kernel,
			'container' => $kernel->container,
            'router' => $kernel->container->getService('router'),
            'http' => array(
				'request' => $kernel->container->getService('http.request'),
				'response' => $kernel->container->getService('http.response')
			),
        );
    }
	
	public function getName()
	{
		return 'PHPPie_Extension';
	}
	
	public function functionRender($string)
	{
		$kernel = \PHPPie\Core\StaticContainer::getService('kernel');
		
		$data = $kernel->findControllerAndAction($string);
		$response = $kernel->executeController($data['controller'], $data['action']);
		return $response->getContent();
	}
	
	public function functionLink($name, $slugs = array())
	{
		return \PHPPie\Core\StaticContainer::getService('kernel')->container->getService('router')->getURI($name, $slugs);
	}
}
