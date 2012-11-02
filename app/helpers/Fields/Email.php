<?php
namespace Helper\Fields;

class Email extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="email" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
