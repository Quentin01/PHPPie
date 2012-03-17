<?php

/*
 * Class Files Request Variables
 * Created on 17/03/12 at 13:54
 */

namespace PHPPie\HTTP\Request;

class Files extends \PHPPie\Core\ArrayData {
	public function __construct($data)
	{
		foreach($data as $key => $value)
		{
			if(!is_array($value["name"]))
			{
				$data[$key] = new File($value);
			}
			else
			{
				$array = array();
				
				foreach($value as $keyValue => $valueValue)
				{
					foreach($value[$keyValue] as $name => $stringValue)
					{
						if(!isset($array[$name]))
							$array[$name] = array();
							
						$array[$name][$keyValue] = $stringValue;
					}
				}
				
				$data[$key] = new self($array);
			}
		}
		
		parent::__construct($data);
	}
	
	public function setConstraintExtension($data)
	{
		foreach($this as $value)
		{
			$value->setConstraintExtension($data);
		}
		
		return $this;
	}
	
	public function setConstraintMaxSize($size)
	{
		foreach($this as $value)
		{
			$value->setConstraintMaxSize($size);
		}
		
		return $this;
	}
	
	public function setConstraintMinSize($size)
	{
		foreach($this as $value)
		{
			$value->setConstraintMinSize($size);
		}
		
		return $this;
	}
	
	public function setUploadDir($dir)
	{
		foreach($this as $value)
		{
			$value->setUploadDir($dir);
		}
		
		return $this;
	}
	
	public function setUploadName($name)
	{
		foreach($this as $value)
		{
			$value->setUploadName($name);
		}
		
		return $this;
	}
	
	public function isValid()
	{
		foreach($this as $value)
		{
			if(($error = $value->isValid()) !== true)
				return $error;
		}
		
		return true;
	}
	
	public function upload($dir)
	{
		foreach($this as $value)
		{
			if(($error = $value->upload($dir)) !== true)
				return $error;
		}
		
		return true;
	}
	
	public function checkExtension($data)
	{
		foreach($this as $value)
		{
			if(($error = $value->checkExtension($data)) !== true)
				return $error;
		}
		
		return true;
	}
	
	public function checkMaxSize($size)
	{
		foreach($this as $value)
		{
			if(($error = $value->checkMaxSize($size)) !== true)
				return $error;
		}
		
		return true;
	}
	
	public function checkMinSize($size)
	{
		foreach($this as $value)
		{
			if(($error = $value->checkMinSize($size)) !== true)
				return $error;
		}
		
		return true;
	}
	
	public function getTotalSize()
	{
		$size = 0;
		
		foreach($this as $value)
		{
			if($value instanceof self)
				$size += (int) $value->getTotalSize();
			else
				$size += (int) $value['tmp_size'];
		}
		
		return $size;
	}
}
