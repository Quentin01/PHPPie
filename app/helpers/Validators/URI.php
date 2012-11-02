<?php
namespace Helper\Validators;

class URI extends \Helper\Validator {
	public function __construct($errorMessage = 'URL non valide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!filter_var($value, FILTER_VALIDATE_URL)) {
			return false;
		}
		
		return true;
	}
}
