<?php

/*
 * Class PHP cache manager
 * Created on 24/10/11 at 13:45
 */

namespace PHPPie\File;

class PHP{

	private cachePath = 'path/to/cache';

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

	public function isFresh($id)
	{
		
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