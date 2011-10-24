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

	public function add($name, $toCache)
	{
		$id = md5($name);
		$cache = create::PHPFile($this->cachePath.$id);
		$cache->writeData('<?php $data = unserialize('.serialize($toCache).'); ?>');
	}

	public function del($id)
	{
		$cache = new PHPFile($this->cachePath.$id);
		$cache->del();
	}

	public function isFresh($id)
	{
		
	}

	public function get($id)
	{
		
	}
}