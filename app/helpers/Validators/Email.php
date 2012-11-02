<?php
namespace Helper\Validators;

class Email extends \Helper\Validator {
	public function __construct($errorMessage = 'Email non valide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		
		return true;
	}
}
