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
		return PHPFile::create($this->getPath($id), $data);
	}
        
    public function getPath($id)
    {
        return $this->cachePath.DIRECTORY_SEPARATOR.$id.'.php';
    }

	public function del($id)
	{
		$cache = new PHPFile($this->getPath($id));
		
		if($cache->exists())
		{
			return $cache->del();
		}
		else
		{
			throw new \PHPPie\Exception\Exception('Cache '.$id.' doesn\'t exists');
		}
	}

	public function isFresh($id, $timeLastUpdateData, $maxTime = 0)
	{
		$cache = new PHPFile($this->getPath($id));
		
		if($cache->exists($id))
		{
			if (is_string($timeLastUpdateData))
			{
				$timeLastUpdateData = filemtime($timeLastUpdateData);
			}

			if ($cache->time < $timeLastUpdateData)
			{
                return false;
			}
            elseif($maxTime != 0 && time() > ($cache->time + $maxTime))
            {
				return false;
            }
            
            return true;
		}
		
		return false;
	}
	
	public function exists($id)
	{
		$cache = new PHPFile($this->getPath($id));
		return $cache->exists();
	}

	public function get($id)
	{
		$cache = new PHPFile($this->getPath($id));
		
		if($cache->exists($id))
		{
			return $cache->readData();
		}
		else
		{
			throw new \PHPPie\Exception\Exception('Cache '.$id.' doesn\'t exists');
		}
	}
}
