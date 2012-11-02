<?php

/*
 * Class ArrayData
 * Created on 17/03/12 at 13:54
 */

namespace PHPPie\Core;

class ArrayData extends \ArrayIterator{
	protected $data = array();
	protected $keys = array();
	
	protected $pos = 0;
	
	public function __construct($data = array())
	{
		$this->data = $data;
		$this->reloadKeys();
	}
	
	public function current()
	{
		return $this->data[$this->key()];
	}
	
	public function key()
	{
		return $this->keys[$this->pos];
	}
	
	public function next()
	{
		$this->pos++;
	}
	
	public function rewind()
	{
		$this->pos = 0;
	}
	
	public function seek($pos)
	{
		$previousPos = $this->pos;
		$this->pos = $pos;
		
		if(!$this->valid())
		{
			trigger_error ('The position isn\'t valid', E_USER_WARNING);
			$this->pos = $previousPos;
		}
	}
	
	public function valid()
	{
		return (isset($this->keys[$this->pos]));
	}
	
	public function offsetExists($key)
	{
		return (isset($this->data[$key]));
	}
	
	public function offsetGet($key)
	{
		if(!isset($this->data[$key])) {
			trigger_error ('The key ' . $key . ' doesn\'t exists', E_USER_ERROR);;
		}
		
		return $this->data[$key];
	}
	
	public function __get($key)
	{
		return $this->offsetGet($key);
	}
	
	public function offsetSet($key, $value)
	{
		$this->data[$key] = $value;
		$this->reloadKeys();
	}
	
	public function __set($key, $value)
	{
		$this->offsetSet($key, $value);
	}
	
	public function offsetUnset($key)
	{
		unset($this->data[$key]);
		$this->reloadKeys();
	}
	
	public function count()
	{
		return count($this->data);
	}
	
	public function append($data)
	{
		$this->data = array_merge($this->data, $data);
		$this->reloadKeys();
	}
	
	protected function reloadKeys()
	{
		if($this->valid())
			$previousKey = $this->key();
			
		$this->keys = array_keys($this->data);
		
		if(!isset($previousKey) || !array_key_exists($previousKey, $this->data))
			$this->rewind();
		else
			$this->pos = array_search($previousKey, $this->keys);
	}
}
