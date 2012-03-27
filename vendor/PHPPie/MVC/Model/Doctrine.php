<?php

/*
 * Doctrine service
 * Created on 24/03/12 at 13:25
 */

namespace PHPPie\MVC\Model;

class Doctrine {
	protected $configuration;
	
	protected $doctrineConfiguration;
	protected $doctrineCache;
	protected $doctrineDriver;
	
	protected $connections = array();
	protected $entityManagers = array();
	
	public function __construct()
	{
		$kernel = \PHPPie\Core\StaticContainer::getService('kernel');
		
		$this->configuration = new \PHPPie\MVC\Model\Doctrine\Configuration($kernel->getPathConfig() . DIRECTORY_SEPARATOR . 'database.yml', $kernel->dev);
	
		if($kernel->dev) 
		{
			$this->doctrineCache = new \Doctrine\Common\Cache\ArrayCache;
		}
		else
		{
			$this->doctrineCache = new \Doctrine\Common\Cache\ApcCache;
		}
	
		$this->doctrineConfiguration = new \Doctrine\ORM\Configuration();
		
		$this->doctrineConfiguration->setMetadataCacheImpl($this->doctrineCache);
		$this->doctrineConfiguration->setQueryCacheImpl($this->doctrineCache);
		
		$this->doctrineDriver = $this->doctrineConfiguration->newDefaultAnnotationDriver($kernel->getPathModels() . DIRECTORY_SEPARATOR . 'Entities');
		$this->doctrineConfiguration->setMetadataDriverImpl($this->doctrineDriver);
		
		$this->doctrineConfiguration->setProxyDir($kernel->getPathModels() . DIRECTORY_SEPARATOR . 'Proxy');
		$this->doctrineConfiguration->setProxyNamespace('Proxy');
		
		if($kernel->dev) 
		{
			$this->doctrineConfiguration->setAutoGenerateProxyClasses(true);
		}
		else
		{
			$this->doctrineConfiguration->setAutoGenerateProxyClasses(false);
		}
	
		$this->loadConnections();
		$this->loadEntityManagers();
	}
	
	protected function loadConnections()
	{
		foreach($this->configuration->getConnections() as $name => $data)
		{
			$this->connections[$name] = \Doctrine\DBAL\DriverManager::getConnection($data, $this->doctrineConfiguration);
		}
	}
	
	protected function loadEntityManagers()
	{
		foreach($this->configuration->getEntityManagers() as $name => $data)
		{
			$nameConnection = "";
			if(!isset($data['connection']))
			{
				$nameConnection = $this->configuration->getNameDefaultConnection();
				
				if($nameConnection === false)
					throw new \PHPPie\Exception\Exception('No default connection for the entity manager : ' . $name,);
			}
			else
			{
				$nameConnection = $data['connection'];
			}
			
			if(!isset($this->connections[$nameConnection]))
				throw new \PHPPie\Exception\Exception('Connection ' . $nameConnection . ' doesn\'t exists for the entity manager : ' . $name);
				
			$this->entityManagers[$name] = \Doctrine\ORM\EntityManager::create($this->connections[$nameConnection], $this->doctrineConfiguration);
		}
	}
	
	public function getEntityManager($name = null)
	{
		if(is_null($name))
		{
			if(($name = $this->configuration->getNameDefaultEntityManager()) === false)
				throw new \PHPPie\Exception\Exception('No name passed to the method and no default entity manager name');
		}
		
		if(!isset($this->entityManagers[$name]))
			throw new \PHPPie\Exception\Exception('The entity manager ' . $name . ' doesn\'t exists');
		
		return $this->entityManagers[$name];
	}
	
	public function getConnection($name = null)
	{
		if(is_null($name))
		{
			if(($name = $this->configuration->getNameDefaultConnection()) === false)
				throw new \PHPPie\Exception\Exception('No name passed to the method and no default connection name');
		}
		
		if(!isset($this->connections[$name]))
			throw new \PHPPie\Exception\Exception('The connection ' . $name . ' doesn\'t exists');
		
		return $this->connections[$name];
	}
}
