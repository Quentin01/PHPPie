<?php
namespace Helper\Fields;

class Checkbox extends \Helper\Field {
	protected function initialize() {
		$this->addValidator(new \Helper\Validators\Checkbox());
	}
	
	public function display($attributes = array())
	{
		return $this->build($attributes);
	}
	
	protected function build($attributes = array()) {
		$content = '<input type="checkbox" name="'.$this->name.'" id="'.$this->name.'" ';
		
		if($this->value == 'on') {
			$content .= 'checked="checked"';
		}
		
		$content .= '/> <label for="'.$this->name.'">'.$this->label.'</label>';
		return $content;
	}
}
