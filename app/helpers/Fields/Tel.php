<?php
namespace Helper\Fields;

class Tel extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="tel" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
