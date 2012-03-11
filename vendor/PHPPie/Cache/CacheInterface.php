<?php

/*
 * Interface cache manager
 * Created on 22/01/12 at 12:00
 */

namespace PHPPie\Cache;

interface CacheInterface {
    public function add($id, $data);
    public function getPath($id);
	public function del($id);
	public function isFresh($id, $timeLastUpdateData, $maxTime = 0);
	public function exists($id);
	public function get($id);
}
?>
