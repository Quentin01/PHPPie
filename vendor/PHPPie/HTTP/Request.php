<?php

/*
 * Class Request
 * Created on 28/10/11 at 10:54
 */

namespace PHPPie\HTTP;

 class Request{
 	
 	protected $get = array();
 	protected $post = array();
 	protected $file = array();
 	protected $server = array();

 	public function __construct(\PHPPie\Core\KernelInterface $kernel)
 	{
		$this->uri = $this->getRequestUri();	
		$this->post = $_POST;
		$this->file = $_FILES;	
		$this->server = $_SERVER;
 	}

 	/**
 	 * Ajoute des données POST dans l'array.
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
 		}
 	}

 	/**
 	 * Retourne la listes des POSTs.
 	 * @param string $index l'index de la variable POST que l'on veut
 	 * @return array Tableau de données ou POST avec l'index demandé
 	 */
 	public function getPost($index = null)
 	{
 		if (!is_null($index))
 		{
 			if (isset($this->post["$index"]))
 			{
 				return $this->post["$index"];
 			}

 			else
 			{
 				throw new \PHPPie\Exception\Exception('The requested index doesn\'t exist.', 'PHPPie\HTTP\Request', 'getPost');
 			}
 		}

 		else
 		{
 			return $this->post;
 		}
 	}

 	/***********************/
 	/**
 	 * Ajoute des paramètres GET dans l'array.
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
 		}
 	}

 	/**
 	 * Méthode qui retourne la liste des GETs ou un en particulier.
 	 * @param string $index l'index de la variable GET que l'on cherche
 	 * @return array Tableau de GET ou celui avec l'index que l'on veut
 	 */
 	public function getGet($index = null)
 	{
 		if (!is_null($index))
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

 	/**
 	 * Ajoute des variables FILES dans l'array.
 	 * @param array $files Tableau des données à ajouter
 	 * @return bool True si ça marche false sinon
 	 */
 	public function addFile($files)
 	{
 		if (is_array($files))
 		{
	 		$this->file = array_merge($this->file, $files);
	 		return true;
 		}

 		else
 		{
 			throw new \PHPPie\Exception\Exception('The argument is not an array.', 'PHPPie\HTTP\Request', 'addFile');
 		}
 	}

 	/**
 	 * Retourne un tableau de file
 	 * @param string $index l'index de la valeur qu'on veut
 	 * @return tableau de FILES ou un en particulier
 	 */
 	public function getFile($index = null)
 	{
 		if (!is_null($index))
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
 	 * @return array tableau de $_SERVER
 	 */ 
	public function getServer()
 	{
 		return $this->server;
 	}

 	/**
 	 * Méthode qui renvoit l'URL de la page précédente
 	 * @return string l'URL de la page précédente
 	 */
 	public function getHttpReferer()
 	{
 		return $_SERVER['HTTP_REFERER'];
 	}

 	/**
 	 * Méthode qui renvoit l'URI demandée par le navigateur
 	 * @return l'URI de la page demandée
 	 */
 	public function getRequestUri()
 	{
 		return $_SERVER['REQUEST_URI'];
 	}

 	/**
 	 * Retourne si une requête est une requêta ajax ou non
 	 * @return bool True si c'est une requête Ajax false sinon
 	 */
	public function isAjaxRequest()
 	{
 		if (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
 		{
 	 		return true;
 	 	}

 	 	else
 		{
 	 		return false;
 		}
 	}
 }