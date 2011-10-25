<?php

/*
 * Class Exception
 * Created on 25/10/11 at 11:12
 */

namespace PHPPie\Exception;

class Exception extends \Exception{

	protected $class;
	protected $method;

	/**
	 * Constructeur d'une exception
	 * @param string $message Le message d'erreur
	 * @param string $class La classe où a lieu l'erreur
	 * @param string $method La méthode où a lieu l'erreur
	 * @return void
	 */ 
	public function __construct($message = null, $class = null, $method = null)
	{
		parent::__construct($message, $code);
		$this->class = $class;
		$this->method = $method;
	}

	/**
	 * Méthode qui met l'objet sous forme de chaîne de caractères.
	 * Elle met en forme le message d'erreur
	 */ 
	public function __toString()
	{
		return 'Error : '.$this->message.' on line '.$this->line.' on class '.$this->class.' and method '.$this->method.' in '.$this->file.'.';
	}
}