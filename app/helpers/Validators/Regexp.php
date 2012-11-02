<?php
namespace Helper\Validators;

class Regexp extends \Helper\Validator {
	protected $regexp;
	
	public function __construct($regexp, $errorMessage = "Valeur invalide") {
		$this->regexp = $regexp;
		$this->errorMessage = $errorMessage;
	}
	
	public function isValid($value) {
		if(!preg_match($this->regexp, $value)) {
			return false;
		}
		
		return true;
	}
}
