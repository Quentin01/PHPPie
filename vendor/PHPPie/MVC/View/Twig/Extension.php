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
            'convertOctet' => new \Twig_Function_Method($this, 'convertOctet', array('is_safe' => array('html'))),
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
			'global' => array(
				'get' => $kernel->container->getService('http.request')->get,
				'post' => $kernel->container->getService('http.request')->post,
				'session' => $kernel->container->getService('http.request')->session
			)
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
		return $kernel->executeController($data['controller'], $data['action'])->getContent();
	}
	
	public function functionLink($name, $slugs = array())
	{
		return \PHPPie\Core\StaticContainer::getService('kernel')->container->getService('router')->getURI($name, $slugs);
	}
	
	public function convertOctet($bytes) {
		$bytes = (double)$bytes;
		$units = array(
			'o',
			'Ko',
			'Mo',
			'Go',
			'To'
		);	
		
		$e = (int)(log($bytes,1024));
		
		if(isset($units[$e]) === false)
			$e = 4;
			
		$bytes = $bytes/pow(1024,$e);
		return round($bytes, 1) . ' ' . $units[$e];
	}
}
