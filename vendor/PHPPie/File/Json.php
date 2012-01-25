<?php

/*
 * Class JSON files manager
 * Created on 23/10/11 at 15:53
 */

namespace PHPPie\File;

class Json extends File {
    public function __construct($pathFile)
    {
        parent::__construct($pathFile);
        
        if($this->getExtension() != 'json')
        {
            throw new \PHPPie\Exception\Exception('File '.$this->pathFile.' isn\'t a JSON file', 'PHPPie\File\Json', '__construct');
        }
    }
    
    public static function create($pathFile)
    {
        return parent::create($pathFile);
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
