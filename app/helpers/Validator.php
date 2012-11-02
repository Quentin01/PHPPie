<?php

namespace Helper;

abstract class Validator {
	protected $form = null;
	protected $field = null;
	protected $errorMessage = '';
	
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	public function setForm($form) {
		$this->form = $form;
	}
	
	public function setField($field) {
		$this->field = $field;
	}
	
	abstract public function isValid($value);
}
