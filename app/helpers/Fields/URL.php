<?php
namespace Helper\Fields;

class URL extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="url" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
