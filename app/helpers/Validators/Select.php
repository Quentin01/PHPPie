<?php
namespace Helper\Validators;

class Select extends \Helper\Validator {
	public function __construct($errorMessage = 'Valeur invalide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!in_array($value, array_keys($this->field->getOptions()))) {
			return false;
		}
		
		return true;
	}
}
