<?php
namespace Helper\Validators;

class IP extends \Helper\Validator {
	public function __construct($errorMessage = 'IP non valide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!filter_var($value, FILTER_VALIDATE_IP)) {
			return false;
		}
		
		return true;
	}
}
