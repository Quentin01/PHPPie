<?php
namespace Helper\Validators;

class Boolean extends \Helper\Validator {
	public function __construct($errorMessage = 'Boolean non valide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
			return false;
		}
		
		return true;
	}
}
