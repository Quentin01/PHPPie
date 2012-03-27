<?php

/*
 * Class Exception
 * Created on 25/10/11 at 11:12
 */

namespace PHPPie\Exception;

class Exception extends \Exception{

	public $statusCode;
	public $trace = array();

	public function __construct($message = null, $statusCode = 200)
	{
		parent::__construct($message);
		$this->statusCode = $statusCode;
		$this->trace = $this->getTraceAsString();
	}

	public function __toString()
	{
            $message = '<strong>Error : </strong>"<em>'.$this->message.'</em>"';
            $message .= ' on line <strong>'.$this->line.'</strong> in <strong>'.$this->file.'</strong>.<br/><br/>';
            $message .= '<strong>Stack trace : </strong>';
            $message .= str_replace('#', '<br/>#', $this->trace) . '<br/>';
            
            return $message;
	}
	
	public function __get($name)
	{
		return (isset($this->$name)) ? $this->$name : false;
	}
	
	public function __set($name, $value)
	{
		$this->$name = $value;
	}
}
