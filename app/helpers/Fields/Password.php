<?php
namespace Helper\Fields;

class Password extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="password" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
