<?php
namespace Helper\Fields;

class Number extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="number" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
