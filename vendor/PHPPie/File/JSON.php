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
        
        if($this->getExtension() != 'json')
        {
            throw new \Exception('File '.$this->pathFile.' isn\'t a JSON\' file');
        }
    }
    
    public static function create($pathFile)
    {
        $file = parent::create($pathFile);
        
        if($file->getExtension() != 'json')
        {
            $file->del();
            throw new \Exception('File '.$file->pathFile.' isn\'t a JSON\' file');
        }
        
        return $file;
    }
    
    public function readArray()
    {
        return json_decode($this->getContents());
    }
    
    public function writeArray(array $array)
    {
        return $this->setContents(json_encode($array));
    }
}

?>