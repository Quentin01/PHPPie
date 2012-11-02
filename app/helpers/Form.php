<?php

namespace Helper;

class Form {
	protected $data = array();
	
	protected $fields = array();
	protected $errorsMessages = array();
	
	public function __construct($data = array())
	{
		$this->data = $data;
	}
	
	public function addField(Field $field)
	{
		$name = $field->name;
		$field->setForm($this);
		
		if(isset($this->data[$name]))
		{
			$field->setValue($this->data[$name]);
		}
		
		$this->fields[$name] = $field;
		return $this;
	}
	
	public function isValid()
	{		
		foreach($this->fields as $field)
		{
			if(!isset($this->data[$field->name]) && !($field instanceof \Helper\Fields\Checkbox))
			{
				$this->errorsMessages[] = array(
					'errors' => array('Aucune valeur donnée'),
					'name' => $field->name
				);
			}
			else
			{
				$field->value = (isset($this->data[$field->name])) ? $this->data[$field->name] : 'off' ;
				
				if(!$field->isValid())
				{
					$this->errorsMessages[] = array(
						'errors' => $field->getErrorsMessages(),
						'name' => $field->name
					);
				}
			}
		}
		
		return (count($this->errorsMessages) === 0);
	}
	
	public function display()
	{
		$content = '';
		
		foreach($this->fields as $field)
		{
			$content .= $field->display() . '<br/>';
		}
		
		return $content;
	}
	
	public function getFields() {
		return $this->fields;
	}
	
	public function getField($name) {
		if(!isset($this->fields[$name]))
			return false;
		
		return $this->fields[$name];
	}
	
	public function setData(array $data) {
		$this->data = array_merge($data, $this->data);
	}
	
	public function getErrorsMessages()
	{
		return $this->errorsMessages;
	}
}
