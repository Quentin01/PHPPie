<?php

/*
 * Class JSON files manager
 * Created on 23/10/11 at 15:53
 */

namespace PHPPie\File;

class Json extends File {
    public function __construct($path, $create = false)
    {
        parent::__construct($path, $create);
        
        if($this->getExtension() != 'json')
        {
            throw new \PHPPie\Exception\Exception('File '.$this->path.' isn\'t a JSON file');
        }
    }
    
    public function readData()
    {
        return json_decode($this->getContents());
    }
    
    public function writeData($data)
    {
        return $this->setContents(json_encode($data));
    }
}

?>
