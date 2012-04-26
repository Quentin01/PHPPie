<?php

/*
 * Configuration of Doctrine
 * Created on 24/03/12 at 13:25
 */

namespace PHPPie\MVC\Model\Doctrine;

class Configuration {
	protected $mode;
	protected $data;
	
	protected $idCache = 'database.cache';
	
	public function __construct($pathfile, $dev = false)
	{
		$cacheManager = \PHPPie\Core\StaticContainer::getService('cache');
		
		if($cacheManager->isFresh($this->idCache, $pathfile))
		{
			$this->data = $cacheManager->get($this->idCache);
		}
		else
		{
			$file = new \PHPPie\File\Yaml($pathfile);
			$this->data = $file->readData();
			
			$cacheManager->add($this->idCache, $this->data);
		}
		
		$this->mode = ($dev) ? "dev" : "prod";
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function getEntityManagers()
	{	
		return array_merge(
			((isset($this->data[$this->mode]['orm']['entity_managers'])) ? $this->data[$this->mode]['orm']['entity_managers'] : array()),
			((isset($this->data['orm']['entity_managers'])) ? $this->data['orm']['entity_managers'] : array())
		);
	}
	
	public function getEntityManager($name)
	{
		$data = (isset($this->data[$this->mode]['orm']['entity_managers'][$name])) ? $this->data[$this->mode]['orm']['entity_managers'][$name] : false;
		$data = (isset($this->data['orm']['entity_managers'][$name])) ? $this->data['orm']['entity_managers'][$name] : $data;
		
		return $data;
	}
	
	public function getNameDefaultEntityManager()
	{
		$name = (isset($this->data[$this->mode]['orm']['default_entity_manager'])) ? $this->data[$this->mode]['orm']['default_entity_manager'] : false;
		$name = (isset($this->data['orm']['default_entity_manager'])) ? $this->data['orm']['default_entity_manager'] : $name;
		
		return $name;
	}
	
	public function getDefaultEntityManager()
	{
		if(($name = $this->getNameDefaultEntityManager()) === false)
			throw new \PHPPie\Exception\Exception('No default entity manager');
			
		return $this->getEntityManager($name);
	}
	
	public function getConnections()
	{
		return array_merge(
			((isset($this->data[$this->mode]['orm']['connections'])) ? $this->data[$this->mode]['orm']['connections'] : array()),
			((isset($this->data['orm']['connections'])) ? $this->data['orm']['connections'] : array())
		);
	}
	
	public function getConnection($name)
	{
		$data = (isset($this->data[$this->mode]['connections'][$name])) ? $this->data[$this->mode]['connections'][$name] : false;
		$data = (isset($this->data['connections'][$name])) ? $this->data['connections'][$name] : $data;
		
		return $data;
	}
	
	public function getNameDefaultConnection()
	{
		$name = (isset($this->data[$this->mode]['orm']['default_connection'])) ? $this->data[$this->mode]['orm']['default_connection'] : false;
		$name = (isset($this->data['orm']['default_connection'])) ? $this->data['orm']['default_connection'] : $name;
		
		return $name;
	}
	
	public function getDefaultConnection()
	{
		if(($name = $this->getNameDefaultConnection()) === false)
			throw new \PHPPie\Exception\Exception('No default connection');
			
		return $this->getConnection($name);
	}
}
