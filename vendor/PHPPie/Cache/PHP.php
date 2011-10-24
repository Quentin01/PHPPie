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
		$cache = create::PHPFile($this->getPath($id));
		$cache->writeData($data);
	}
        
        public function getPath($id)
        {
            return $this->cachePath.DIRECTORY_SEPARATOR.md5($id);
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
			throw new \Exception('Cache '.$id.' doesn\'t exists');
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
			throw new \Exception('Cache '.$id.' doesn\'t exists');
		}
	}
}