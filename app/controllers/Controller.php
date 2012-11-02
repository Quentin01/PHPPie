<?php

class Controller extends \PHPPie\MVC\Controller {
	
	public function index()
	{
		/*$response = $this->get('http.response');
		$response->setContent('Test');
		
		return $response;*/
		
		//return "index";
		
		//return $this->get('view', 'Test');
		
		/*$doctrine = $this->get('doctrine');
		$em = $doctrine->getEntityManager();*/
		
		$view = $this->get('view'); // Egale à $this->get('view', 'Controller/index');
		
		/*$form = new Helper\Form();
		
		$field = new Helper\Fields\Text('test', 'value');
		$field->addValidator(new Helper\Validators\MaxLength(10))
			  ->addValidator(new Helper\Validators\MinLength(2));
		
		$form->addField($field);
		
		$field = new Helper\Fields\Textarea('test2', 'value2');
		
		$field->addValidator(new Helper\Validators\MaxLength(10))
			  ->addValidator(new Helper\Validators\Float())
			  ->addValidator(new Helper\Validators\Equal('test', 'La valeur de test2 est différente de test'));
		
		$form->addField($field);
		
		$field = new Helper\Fields\Select('test3', '', 'value3');
		
		$field->add('Frite', 'value1')
			  ->add('Sandwich', 'value2')
			  ->add('Steak', 'value3');
		
		$form->addField($field);
		
		$field = new Helper\Fields\Checkbox('test4', 'CGU');
		$form->addField($field);
		
		if(!empty($_POST)) {
			$form->setData($_POST);
			
			if(!$form->isValid()) {
				// Formulaire invalide
			} else {
				// Formulaire valide
			}
		}		
		$view->addVariables(array('form' => $form));*/
		
		/*$pagination = new \Helper\Pagination("www.test.com/{page}", 9, 5, array(
			'dsn' => 'mysql:host=localhost;dbname=multiuploader',
			'user' => 'root',
			'password' => ''
		));
		
		$view->addVariable('datas', $pagination->getDataWithSQL('upload', $options = ''));
		
		$message = $pagination->getFormatedLinks();
		$view->addVariable('message', $message);*/
		
		return $view;
	}
	
	public function test()
	{
		$view = $this->get('view');
		$view->addVariables(array('name' => 'Quentin01'));
		return $view;
	}
}
