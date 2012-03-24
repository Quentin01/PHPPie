<?php

class Controller extends \PHPPie\MVC\Controller {
	
	public function index()
	{
		/*$response = $this->get('http.response');
		$response->setContent('Test');
		
		return $response;*/
		
		//return "index";
		
		//return $this->get('view', 'Test');
		
		$doctrine = $this->get('doctrine');
		$em = $doctrine->getEntityManager();
		
		$view = $this->get('view'); // Egale à $this->get('view', 'Controller/index');
		$view->addVariables(array('name' => 'Quentin'));
		return $view;
	}
	
	public function test()
	{
		$view = $this->get('view');
		$view->addVariables(array('name' => 'Quentin01'));
		return $view;
	}
}
