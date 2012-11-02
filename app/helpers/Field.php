<?php

namespace Helper;

abstract class Field {
	public $name;
	public $value;
	public $label;
	
	protected $form = null;
	protected $validators = array();
	protected $errorsMessages = array();
	
	public function __construct($name, $label = "", $value = "", $validators = array())
	{
		$this->name = $name;
		$this->value = $value;
		$this->label = $label;
		$this->validators = $validators;
		
		$this->initialize();
	}
	
	protected function initialize() {}
	
	public function isValid()
	{
		foreach($this->validators as $validator)
		{
			if(!$validator->isValid($this->value))
			{
				$this->errorsMessages[] = $validator->getErrorMessage();
			}
		}
		
		return (count($this->errorsMessages) === 0);
	}
	
	public function addValidator(Validator $validator)
	{
		$validator->setField($this);
		$validator->setForm($this->form);
		
		$this->validators[] = $validator;
		return $this;
	}
	
	public function display($attributes = array())
	{
		$content = '';
		
		if(!empty($this->label))
			$content .= '<label for="'.$this->name.'">'.$this->label.'</label>';
		
		return $content . $this->build($attributes);
	}
	
	public function setForm(Form $form) {
		$this->form = $form;
		
		foreach($this->validators as $validator) {
			$validator->setForm($form);
		}
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function displayAttributes($attributes = array()) {
		$content = '';
		
		foreach($attributes as $name => $value) {
			$content .= $name . ' = "' . $value . '" ';
		}
		
		return $content;
	}
	
	public function getErrorsMessages()
	{
		return $this->errorsMessages;
	}
	
	abstract protected function build($attributes = array());
}
