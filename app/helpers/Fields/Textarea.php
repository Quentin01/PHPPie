<?php
namespace Helper\Fields;

class Textarea extends \Helper\Field {
	protected function build($attributes = array()) {
		return '<textarea name="'.$this->name.'" id="'.$this->name.'" '.$this->displayAttributes($attributes).'>' . $this->value . '</textarea>';
	}
}
