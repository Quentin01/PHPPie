<?php
namespace Helper\Validators;

class Float extends \Helper\Validator {
	public function __construct($errorMessage = 'Nombre à virgule non valide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		$value = str_replace(',', '.', $value);
		
		if(!filter_var($value, FILTER_VALIDATE_FLOAT)) {
			return false;
		}
		
		return true;
	}
}
