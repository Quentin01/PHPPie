<?php
namespace Helper\Fields;

class Radio extends \Helper\Field {
	protected $options = array();
	
	protected function initialize() {
		$this->addValidator(new \Helper\Validators\Select());
	}
	
	public function add($label, $value) {
		$this->options[$value] = $label;
		return $this;
	}
	
	public function display($attributes = array())
	{
		return $this->build($attributes);
	}
	
	protected function build($attributes = array()) {
		return implode('<br/>' . "\n", $this->getFormatedOptions());
	}
	
	public function getOptions() {
		return $this->options;
	}
	
	public function getFormatedOptions() {
		$options = array();
		
		foreach($this->options as $value => $label) {
			$content = '<input type="radio" name="'.$this->name.'" value="'.$value.'" id="'.$value.'" ';
			
			if($value == $this->value) {
				$content .= 'checked="checked"';
			}
			
			$content .= '/> <label for="'.$value.'">'.$label.'</label>';
			
			$options[] = $content;
		}
		
		return $options;
	}
}
