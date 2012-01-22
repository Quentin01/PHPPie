<?php

/*
 * Class PHP cache manager
 * Created on 24/10/11 at 13:45
 */

namespace PHPPie\Cache;
use \PHPPie\File\PHP as PHPFile;

class PHP implements CacheInterface{

	protected $cachePath;

	public function __construct($cachePath)
	{
		$this->cachePath = $cachePath;
	}

	public function add($id, $data)
	{
		$cache = PHPFile::create($this->getPath($id));
		$cache->writeData($data);
	}
        
    public function getPath($id)
    {
        return $this->cachePath.DIRECTORY_SEPARATOR.$id.'.php';
    }

	public function del($id)
	{
		if($this->exists($id))
		{
			$cache = new PHPFile($this->getPath($id));
			$cache->del();
		}
		else
		{
			throw new \PHPPie\Exception\Exception('Cache '.$id.' doesn\'t exists', 'PHPPie\Cache\PHP', 'del');
		}
	}

	public function isFresh($id, $timeLastUpdateData, $maxTime = 0)
	{
		if($this->exists($id))
		{
			if (is_string($timeLastUpdateData))
			{
				$timeLastUpdateData = filemtime($timeLastUpdateData);
			}

			$timeLastUpdateCache = filemtime($this->getPath($id));
			
			if ($timeLastUpdateCache < $timeLastUpdateData)
			{
                return false;
			}
            elseif($maxTime != 0 && time() > ($timeLastUpdateCache + $maxTime))
            {
				return false;
            }
			else
			{
                return True;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function exists($id)
	{
		try
		{
			$cache = new PHPFile($this->getPath($id));
		}
		catch(\Exception $e)
		{
			return false;
		}
		
		return true;
	}

	public function get($id)
	{
		if($this->exists($id))
		{
			$cache = new PHPFile($this->getPath($id));
			return $cache->readData();
		}
		else
		{
			throw new \PHPPie\Exception\Exception('Cache '.$id.' doesn\'t exists', 'PHPPie\Cache\PHP', 'get');
		}
	}
}
