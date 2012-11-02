<?php
namespace Helper\Validators;

class Checkbox extends \Helper\Validator {
	public function __construct($errorMessage = 'Valeur invalide') {
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!in_array($value, array('on', 'off'))) {
			return false;
		}
		
		return true;
	}
}
