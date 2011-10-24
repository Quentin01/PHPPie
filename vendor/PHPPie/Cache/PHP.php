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

	public function add($name, $fileToCache)
	{
		$id = md5($this->cachePath.$name);
		$file = $fileToCache->readData();
		$cache = create::PHPFile($this->cachePath.$id);
		$cache->writeData(serialize($file));
	}

	public function del($id)
	{
		
	}

	public function isFresh($id)
	{
		
	}

	public function get($id)
	{
		
	}
}