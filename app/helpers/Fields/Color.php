<?php
namespace Helper\Fields;

class Color extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="color" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
