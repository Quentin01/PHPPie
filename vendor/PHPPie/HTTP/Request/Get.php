<?php

/*
 * Class Get Request Variables
 * Created on 17/03/12 at 13:54
 */

namespace PHPPie\HTTP\Request;

class Get extends \PHPPie\Core\ArrayData {
	public function __construct($data)
	{
		foreach($data as $key => $value)
		{
			$data[$key] = new self($value);
		}
		
		parent::__construct($data);
	}
}
