<?php
namespace Helper\Fields;

class Text extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="text" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
