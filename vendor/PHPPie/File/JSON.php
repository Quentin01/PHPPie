<?php

/*
 * Class JSON files manager
 * Create on 23/10/11 at 15:53
 */

namespace PHPPie\File;

class JSON extends File {
    public function __construct($pathFile)
    {
        parent::__construct($pathFile);
        
        $extension = explode('.', $this->name);
        $extension = $extension[1];
        
        if($extension != 'json')
            throw new \Exception('File '.$this->pathFile.' isn\'t a JSON\' file');
    }
    
    public function toArray()
    {
        return json_decode($this->getContents());
    }
    
    public function writeArray(array $array)
    {
        return $this->setContents(json_encode($array));
    }
}

?>