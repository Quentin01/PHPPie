<?php
namespace Helper\Fields;

class Range extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="range" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
