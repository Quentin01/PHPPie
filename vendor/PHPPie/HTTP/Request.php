<?php

/*
 * Class Request
 * Created on 28/10/11 at 10:54
 */

namespace PHPPie\HTTP;

 class Request{
 	
 	private $uri;
 	private $get = array();
 	private $post = array();
 	private $file = array();
 	private $server = $_SERVER;

 	public function __construct(\PHPPie\Core\KernelInterface $kernel)
 	{
		$this->uri = getRequestUri();	
		$this->post = $_POST;
		$this->file = $_FILES;	
 	}

 	public function getUri()
 	{
 		return $this->uri;
 	}

 	/***********************/
 	/**
 	 * Méthode qui ajoute des données POST dans l'array.
 	 * @param array $posts Tableau des données à ajouter
 	 * @return bool True si ça marche false sinon
 	 */
 	public function addPost($posts)
 	{
 		if (is_array($posts))
 		{
	 		$this->post = array_merge($this->post, $posts);
	 		return true;
 		}

 		else
 		{
 			throw new \PHPPie\Exception\Exception('The argument is not an array.', 'PHPPie\HTTP\Request', 'addPost');
 			return false;
 		}
 	}

 	/**
 	 * Méthode qui retourne la listes des POSTs.
 	 * @return array Tableau de données
 	 */
 	public function getPost()
 	{
 		return $this->post;
 	}

 	/***********************/
 	/**
 	 * Méthode qui ajoute des paramètres GET dans l'array.
 	 * @param array $gets Tableau des données à ajouter
 	 * @return bool True si ça marche false sinon
 	 */
 	public function addGet($gets)
 	{
 		if (is_array($gets))
 		{
	 		$this->get = array_merge($this->get, $gets);
	 		return true;
 		}

 		else
 		{
 			throw new \PHPPie\Exception\Exception('The argument is not an array.', 'PHPPie\HTTP\Request', 'addGet');
 			return false;
 		}
 	}

 	/**
 	 * Méthode qui retourne la listes des GETs.
 	 * @param string index
 	 * @return array Tableau de données
 	 */
 	public function getGet($index = null)
 	{
 		if (!empty($index))
 		{
 			if (isset($this->get["$index"]))
 			{
 				return $this->get["$index"];
 			}

 			else
 			{
 				throw new \PHPPie\Exception\Exception('The requested index doesn\'t exist.', 'PHPPie\HTTP\Request', 'getGet');
 			}
 		}

 		else
 		{
 			return $this->get;
 		}
 	}

 	/***********************/
 	/**
 	 * Méthode qui ajoute des variables FILES dans l'array.
 	 * @param array $files Tableau des données à ajouter
 	 * @return bool True si ça marche false sinon
 	 */
 	public function addFile($files)
 	{
 		if (is_array($gets))
 		{
	 		$this->file = array_merge($this->file, $files);
	 		return true;
 		}

 		else
 		{
 			throw new \PHPPie\Exception\Exception('The argument is not an array.', 'PHPPie\HTTP\Request', 'addFile');
 			return false;
 		}
 	}

 	/**
 	 * Méthode qui renvoit un tableau de file
 	 */
 	public function getFile($index = null)
 	{
 		if (!empty($index))
 		{
 			if (isset($this->file["$index"]))
 			{
 				return $this->file["$index"]
 			}

 			else
 			{
 				throw new \PHPPie\Exception\Exception('The requested index doesn\'t exist.', 'PHPPie\HTTP\Request', 'getFile');
 			}
 		}

 		else
 		{
 			return $this->file;
 		}
 	}

 	/***********************/
 	/**
 	 * Méthode qui renvoit un tableau SERVER
 	 */ 
	public function getServer()
 	{
 		return $this->server;
 	}

 	/**
 	 * Méthode qui renvoit l'URL de la page précédente
 	 */
 	public function getHttpReferer()
 	{
 		return $_SERVER['HTTP_REFERER'];
 	}

 	/**
 	 * Méthode qui renvoit l'URI demandée par le navigateur
 	 */
 	public function getRequestUri()
 	{
 		return $_SERVER['REQUEST_URI'];
 	}
 }