<?php
namespace Helper\Validators;

class Int extends \Helper\Validator {
	public function __construct($errorMessage = 'Nombre non valide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!filter_var($value, FILTER_VALIDATE_INT)) {
			return false;
		}
		
		return true;
	}
}
