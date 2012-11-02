<?php
namespace Helper\Validators;

class Equal extends \Helper\Validator {
	protected $fieldName;
	
	public function __construct($fieldName, $errorMessage = null) {
		$this->fieldName = $fieldName;
		
		if($errorMessage !== null)
			$this->errorMessage = $errorMessage;
		else
			$this->errorMessage = "La valeur ne correspond pas à " . $fieldName;
	}
	
	public function isValid($value) {
		$field = $this->form->getField($this->fieldName);
		
		if($field === false) {
			$this->errorMessage = "Le champ " . $this->fieldName . " n'existe pas";
			return false;
		}
		
		if($field->value != $this->field->value) {
			return false;
		}
		
		return true;
	}
}
