<?php

/*
 * PHP View
 * Created on 27/04/12 at 23:00
 */

namespace PHPPie\MVC\View;

class Php extends \PHPPie\MVC\View {
	public function getExtensionFile()
	{
		return '.php';
	}

	public function render()
	{
		ob_start();
		
		extract($this->variables);
		include $this->getRealPathfile();
		
		return ob_get_clean();
	}
}
