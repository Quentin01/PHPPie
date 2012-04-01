<?php

/*
 * Event listener for asset files
 * Created on 01/04/12 at 15:50
 */

namespace PHPPie\Event\Listeners;

class Assets extends \PHPPie\Event\Listener{
	public function __construct()
	{
		
	}
	
	protected function minifyJS($contents)
	{
		
		return $contents;
	}
	
	protected function minifyCSS($contents)
	{
		$contents = preg_replace('#/\*(.*)\*/#U', '', $contents);
		$contents = preg_replace('#\s+#', ' ', $contents);
		$contents = preg_replace('#\s?(;|:|{|}|,)\s?#', '$1', $contents);
		return $contents;
	}
	
	public function onAssetFileFound(&$routingURI, &$pathFile)
	{
		$kernel = \PHPPie\Core\StaticContainer::getService('kernel');
		$response = \PHPPie\Core\StaticContainer::getService('http.response');
		$server = \PHPPie\Core\StaticContainer::getService('http.request')->server;
		
		$extension = substr(strrchr(basename($pathFile), '.'), 1);
		$pathCacheFile = $kernel->getPathCache() . DIRECTORY_SEPARATOR . md5($routingURI);
		
		if(!in_array($extension, array('js', 'css')))
				return;
		
		$gzip = strstr($server['HTTP_ACCEPT_ENCODING'], 'gzip');
		$deflate = strstr($server['HTTP_ACCEPT_ENCODING'], 'deflate');
	
		$encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');
				
		if (!strstr($server['HTTP_USER_AGENT'], 'Opera') && preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $server['HTTP_USER_AGENT'], $matches)) {
			$version = floatval($matches[1]);
				
			if ($version < 6)
				$encoding = 'none';
				
			if ($version == 6 && !strstr($server['HTTP_USER_AGENT'], 'EV1')) 
				$encoding = 'none';
		}
				
		if ($encoding != 'none') {
			$response->setHeader("Content-Encoding", $encoding);
			$pathCacheFile .= '-' . $encoding;
		}
		
		$pathCacheFile = $pathCacheFile . '.' . $extension;
		
		if(!file_exists($pathCacheFile) || filemtime($pathCacheFile) <= filemtime($pathFile))
		{
			$contents = file_get_contents($pathFile);
			$methodName = "minify" . strtoupper($extension);
			
			$contents = $this->$methodName($contents);
			
			if ($encoding != 'none')
				$contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
				
			file_put_contents($pathCacheFile, $contents);
		}
		
		if($extension == "js")
			$response->setHeader('Content-Type', 'text/javascript');
		elseif($extension == "css")
			$response->setHeader('Content-Type', 'text/css');
		
		$pathFile = $pathCacheFile;
		$response->setHeader('Content-Length', filesize($pathCacheFile));
	}
}
