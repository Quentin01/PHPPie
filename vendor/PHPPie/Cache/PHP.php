<?php

/*
 * Class PHP cache manager
 * Created on 24/10/11 at 13:45
 */

namespace PHPPie\File;

class PHP{

	private $cachePath = 'path/to/cache';

	public function __construct()
	{
		
	}

	public function add($id, $data)
	{
		$id = md5($id);
		$cache = create::PHPFile($this->cachePath.$id);
		$cache->writeData($data);
	}

	public function del($id)
	{
		if($this->isset($id))
		{
			$id = md5($id);
			$cache = new PHPFile($this->cachePath.$id);
			$cache->del();
		}
		else
		{
			throw new \Exception('Cache '.$id.' doesn\'t exists');
		}
	}

	public function isFresh($id, $dateLastModifFile, $maxCacheLifeTime = 0)
	{
		if ($this->isset($id))
		{
			if (is_string($dateLastModifFile))
			{
				$dateLastModifFile = intval($dateLastModifFile);
			}

			$id = md5($id);
			$nameFile = $this->cachePath.$id;
			$dateLastModificationCache = filemtime($nameFile);
			$actualTime = time();
			
			if ($dateLastModificationCache < $dateLastModifFile)
			{
				return False;
			}

			elseif ($maxCacheLifeTime != 0 AND $actualTime > $dateLastModificationCache + $maxCacheLifeTime)
			{
				return False;
			}

			else
			{
				return True;
			}
		}

		else
		{
			throw new \Exception('Cache '.$id.' doesn\'t exists');
		}
	}
	
	public function isset($id)
	{
		$id = md5($id);
		
		try
		{
			$cache = new PHPFile($this->cachePath.$id);
		}
		catch($e)
		{
			return false;
		}
		
		return true;
	}

	public function get($id)
	{
		if($this->isset($id))
		{
			$id = md5($id);
			$cache = new PHPFile($this->cachePath.$id);
			return $cache->readData();
		}
		else
		{
			throw new \Exception('Cache '.$id.' doesn\'t exists');
		}
	}
}