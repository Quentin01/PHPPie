<?php

/*
 * Class File Request Variables
 * Created on 17/03/12 at 13:54
 */

namespace PHPPie\HTTP\Request;

class File extends \PHPPie\Core\ArrayData {
	protected $extensions = null;
	protected $maxsize = null;
	protected $minsize = null;
	
	protected $rename = null;
	protected $dir = null;
	
	const BAD_EXTENSION = 1;
	const SIZE_TOO_LARGE = 2;
	const SIZE_TOO_SMALL = 3;
	
	const IMPOSSIBLE_UPLOAD = 100;
	
	public function __construct($data)
	{
		$data['extension'] = substr(strrchr(basename($data['name']), '.'), 1);
		$data['tmp_size'] = filesize($data['tmp_name']);
		
		parent::__construct($data);
	}
	
	public function setConstraintExtension($data)
	{
		$this->extensions = $data;
		return $this;
	}
	
	public function setConstraintMaxSize($size)
	{
		$this->maxsize = $size;
		return $this;
	}
	
	public function setConstraintMinSize($size)
	{
		$this->minsize = $size;
		return $this;
	}
	
	public function setUploadDir($dir)
	{
		$this->dir = $dir;
		return $this;
	}
	
	public function setUploadName($name)
	{
		$this->rename = $name;
		return $this;
	}
	
	public function isValid()
	{
		if(!is_null($this->extensions))
		{
			if(!$this->checkExtension($this->extensions))
			{
				return $this->returnError(self::BAD_EXTENSION);
			}
		}
		
		if(!is_null($this->maxsize))
		{
			if(!$this->checkMaxSize($this->maxsize))
			{
				return $this->returnError(self::SIZE_TOO_LARGE);
			}
		}
		
		if(!is_null($this->minsize))
		{
			if(!$this->checkMinSize($this->minsize))
			{
				return $this->returnError(self::SIZE_TOO_SMALL);
			}
		}
		
		if((int) $this->offsetGet('error') != UPLOAD_ERR_OK)
			return $this->returnError((int) $this->offsetGet('error'));
		
		return true;
	}
	
	public function upload($dir)
	{
		if(($error = $this->isValid()) !== true)
		{
			return $error;
		}
		
		if(!is_null($this->dir))
		{
			$dir = $this->dir;
		}
		
		$dir = (substr($dir, -1, 1) === DIRECTORY_SEPARATOR) ? $dir : $dir . DIRECTORY_SEPARATOR;
		$name = (is_null($this->rename)) ? $this->offsetGet('name') : $this->rename;
		
		if(move_uploaded_file($this->offsetGet('tmp_name'), $dir . $name)
		{
			return true;
		}
		else
		{
			return $this->returnError(self::IMPOSSIBLE_UPLOAD);
		}
	}
	
	public function checkExtension($data)
	{
		if(is_string($data))
			$data = array($data);
			
		return (in_array($this->offsetGet('extension'), $data));
	}
	
	public function checkMaxSize($size)
	{
		return ((int) $this->offsetGet('tmp_size') <= (int) $size);
	}
	
	public function checkMinSize($size)
	{
		return ((int) $this->offsetGet('tmp_size') >= (int) $size);
	}
	
	protected function returnError($error)
	{
		return array('file' => $this, 'error' => $error);
	}
}
