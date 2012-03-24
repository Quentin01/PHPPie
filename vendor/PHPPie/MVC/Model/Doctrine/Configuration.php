<?php

/*
 * Configuration of Doctrine
 * Created on 24/03/12 at 13:25
 */

namespace PHPPie\MVC\Model\Doctrine;

class Configuration {
	protected $mode;
	protected $data;
	
	public function __construct($pathfile, $dev = false)
	{
		$file = new \PHPPie\File\Yaml($pathfile);
        $this->data = $file->readData();
        
        $this->mode = ($dev) ? "dev" : "prod";
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function getEntityManagers()
	{
		$data = array();
		
		if(isset($this->data[$this->mode]['orm']['entity_managers']))
		{
			foreach($this->data[$this->mode]['orm']['entity_managers'] as $name => $entityManager)
			{
				$data[$name] = $entityManager;
			}
		}
		
		if(isset($this->data['orm']['entity_managers']))
		{
			foreach($this->data['orm']['entity_managers'] as $name => $entityManager)
			{
				$data[$name] = $entityManager;
			}
		}
		
		return $data;
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
			throw new \PHPPie\Exception\Exception('No default entity manager', 'PHPPie\MVC\Model\Doctrine\Configuration');
			
		return $this->getEntityManager($name);
	}
	
	public function getConnections()
	{
		$data = array();
		
		if(isset($this->data[$this->mode]['connections']))
		{
			foreach($this->data[$this->mode]['connections'] as $name => $connection)
			{
				$data[$name] = $connection;
			}
		}
		
		if(isset($this->data['connections']))
		{
			foreach($this->data['connections'] as $name => $connection)
			{
				$data[$name] = $connection;
			}
		}
		
		return $data;
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
			throw new \PHPPie\Exception\Exception('No default connection', 'PHPPie\MVC\Model\Doctrine\Configuration');
			
		return $this->getConnection($name);
	}
}
