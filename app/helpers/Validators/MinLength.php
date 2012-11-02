<?php
namespace Helper\Validators;

class MinLength extends \Helper\Validator {
	protected $length;
	
	public function __construct($length, $errorMessage = 'Valeur trop courte, minimum {length} caractères') {
		$this->length = $length;
		$this->errorMessage = str_replace('{length}', $length, $errorMessage);
	}
	
	public function isValid($value) {
		if(strlen($value) < $this->length) {
			return false;
		}
		
		return true;
	}
}
