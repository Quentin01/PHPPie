<?php

/*
 * Twig Extension
 * Created on 11/03/12 at 16:30
 */

namespace PHPPie\MVC\View\Twig;

class Extension extends \Twig_Extension {
	protected $kernel;
	
	public function __construct(\PHPPie\Core\KernelInterface $kernel)
	{
		$this->kernel = $kernel;
	}
	
	public function getFunctions()
    {
        return array(
            'render' => new \Twig_Function_Method($this, 'functionRender', array('is_safe' => array('html'))),
        );
    }
	
	public function getName()
	{
		return 'PHPPie_Extension';
	}
	
	public function functionRender($string)
	{
		$data = $this->kernel->findControllerAndAction($string);
		$response = $this->kernel->executeController($data['controller'], $data['action']);
		return $response->getContent();
	}
}
