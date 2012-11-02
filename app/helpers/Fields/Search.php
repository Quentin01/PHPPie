<?php
namespace Helper\Fields;

class Search extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<input type="search" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'/>';
	}
}
