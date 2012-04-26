<?php

/*
 * Event listener for asset files
 * Created on 01/04/12 at 15:50
 */

namespace PHPPie\Event\Listeners;

class Assets extends \PHPPie\Event\Listener{
	protected $minify = true;
	protected $encode = true;
	
	public function __construct($minify = true, $encode = true)
	{
		$this->minify = $minify;
		$this->encode = $encode;
	}
	
	protected function minifyJS($contents)
	{
		$jsxs = new \Jsxs();

		$jsxs->setRegexDirectory(\PHPPie\Core\StaticContainer::getService('kernel')->dirFrontController . '/vendor/jsxs/preg');
		$jsxs->setCompatibility(true);
		$jsxs->setReduce(true);
		$jsxs->setShrink(true);
		$jsxs->setConcatString(true);

		return $jsxs->exec($contents);
	}
	
	protected function minifyCSS($contents)
	{
		$contents = preg_replace('#/\*(.*)\*/#U', '', $contents);
		$contents = preg_replace('#\s+#', ' ', $contents);
		$contents = preg_replace('#\s?(;|:|{|}|,)\s?#', '$1', $contents);
		return $contents;
	}
	
	protected function compressFile($pathFile)
	{
		$extension = substr(strrchr(basename($pathFile), '.'), 1);
		
		$contents = file_get_contents($pathFile);
		$methodName = "minify" . strtoupper($extension);
		
		if(!$this->minify)
			return $contents;
		
		$contents = $this->$methodName($contents);
				
		return $contents;
	}
	
	protected function encodeContents($contents)
	{
		$encoding = $this->getEncodingSupported();
		
		if ($encoding != 'none')
			$contents = gzencode($contents, 9, (($encoding == "gzip") ? FORCE_GZIP : FORCE_DEFLATE));
			
		return $contents;
	}
	
	protected function getEncodingSupported()
	{
		if(!$this->encode)
			return "none";
			
		$server = \PHPPie\Core\StaticContainer::getService('http.request')->server;
		
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
		
		return $encoding;
	}
	
	protected function checkExtension($extension)
	{
		return in_array($extension, array('js', 'css'));
	}
	
	protected function sendHeaders($pathCacheFile)
	{
		$response = \PHPPie\Core\StaticContainer::getService('http.response');
		
		$encoding = $this->getEncodingSupported();
		$extension = substr(strrchr(basename($pathCacheFile), '.'), 1);
		
		if ($encoding != 'none')
			$response->setHeader("Content-Encoding", $encoding);
			
		if($extension == "js")
			$response->setHeader('Content-Type', 'text/javascript');
		elseif($extension == "css")
			$response->setHeader('Content-Type', 'text/css');
		
		$response->setHeader('Content-Length', filesize($pathCacheFile));
	}
	
	protected function getPathCache($routingURI, $extension)
	{
		$encoding = $this->getEncodingSupported();
		$kernel = \PHPPie\Core\StaticContainer::getService('kernel');
		
		return $kernel->getPathCache() . DIRECTORY_SEPARATOR . (($this->minify) ? 'min-' : '') . md5($routingURI) . (($encoding !== "none") ? '-' . $encoding : "") . '.' . $extension;
	}
	
	public function onAssetFileNotFound(&$routingURI)
	{
		if(($pos = strpos($routingURI, '/web/')) === false)
		{
			return false;
		}
		
		$kernel = \PHPPie\Core\StaticContainer::getService('kernel');
		$response = \PHPPie\Core\StaticContainer::getService('http.response');
		
		$files = explode(';', substr($routingURI, $pos));
		
		$dir = dirname($files[0]);
		$files[0] = substr($files[0], strlen($dir) + 1);
		
		$filemtime = 0;
		$extension = substr(strrchr($files[0], '.'), 1);
		
		foreach($files as $key => $file)
		{
			if(!file_exists($files[$key] = $kernel->dirFrontController . $dir . DIRECTORY_SEPARATOR . $file))
			{
				return false;
			}
			elseif(substr(strrchr($files[$key], '.'), 1) != $extension)
			{
				return false;
			}
			else
			{
				if(filemtime($files[$key]) > $filemtime)
				{
					$filemtime = filemtime($files[$key]);
				}
			}
		}
		
		if($this->checkExtension($extension) === false)
				return false;
		
		$pathCacheFile = $this->getPathCache($routingURI, $extension);
		
		if(!file_exists($pathCacheFile) || filemtime($pathCacheFile) <= $filemtime)
		{
			$contents = "";
			
			foreach($files as $file)
			{
				$contents .= ' ' . $this->compressFile($file);
			}
			
			file_put_contents($pathCacheFile, $this->encodeContents($contents));
		}
		
		$this->sendHeaders($pathCacheFile);
		
		$response->setContent(file_get_contents($pathCacheFile));
		$response->send();
		
		return true;
	}
	
	public function onAssetFileFound(&$routingURI, &$pathFile)
	{
		$extension = substr(strrchr(basename($pathFile), '.'), 1);
		
		if($this->checkExtension($extension) === false)
				return false;
		
		$pathCacheFile = $this->getPathCache($routingURI, $extension);
		
		if(!file_exists($pathCacheFile) || filemtime($pathCacheFile) <= filemtime($pathFile))
		{
			file_put_contents($pathCacheFile, $this->encodeContents($this->compressFile($pathFile)));
		}
		
		$this->sendHeaders($pathCacheFile);
		$pathFile = $pathCacheFile;
		
		return true;
	}
}
