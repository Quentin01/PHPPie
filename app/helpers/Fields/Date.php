<?php
namespace Helper\Fields;

class Date extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="date" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
