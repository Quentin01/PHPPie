<?php
namespace Helper\Fields;

class Select extends \Helper\Field {
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
		$content = '<select name="'.$this->name.'" '.$this->displayAttributes($attributes).'>';
		$content .= implode("\n", $this->getFormatedOptions());
		$content .= '</select>';
		return $content;
	}
	
	public function getOptions() {
		return $this->options;
	}
	
	public function getFormatedOptions() {
		$options = array();
		
		foreach($this->options as $value => $label) {
			$content = '<option value="'.$value.'" ';
			
			if($value == $this->value) {
				$content .= 'selected="selected"';
			}
			
			$content .= '/>'.$label.'</option>';
			
			$options[] = $content;
		}
		
		return $options;
	}
}
