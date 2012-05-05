<?php

/*
 * PHP View
 * Created on 27/04/12 at 23:00
 */

namespace PHPPie\MVC\View;

class Php extends \PHPPie\MVC\View {
	protected $layout = null;
	
	public function getExtensionFile()
	{
		return '.php';
	}

	public function render()
	{
		ob_start();
		
		extract($this->variables);
		include $this->getRealPathfile();
		
		$contents = ob_get_clean();
		
		if(is_null($this->layout))
			return $contents;
			
		$layout = new self($this->layout);
		
		ob_start();
		include $layout->getRealPathfile();
		return ob_get_clean();
		
	}
	
	public function extend($layout)
	{
		$this->layout = $layout;
	}
}
