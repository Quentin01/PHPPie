<?php

/*
 * Class PHP files manager
 * Created on 24/10/11 at 11:13
 */

namespace PHPPie\File;

class PHP extends File {
    public function __construct($pathFile)
    {
        parent::__construct($pathFile);
        
        if($this->getExtension() != 'php' || $this->getExtension() != 'php5')
        {
            throw new \Exception('File '.$this->pathFile.' isn\'t a PHP\' file');
        }
    }
    
    public static function create($pathFile)
    {
        $file = parent::create($pathFile);
        
        if($file->getExtension() != 'php' || $file->getExtension() != 'php5')
        {
            $file->del();
            throw new \Exception('File '.$file->pathFile.' isn\'t a PHP\' file');
        }
        
        return $file;
    }
    
    public function readData()
    {
        include $this->pathFile;
        return $data;
    }
    
    public function writeData($data)
    {
        return $this->setContents("<?php $data = unserialize('".serialize($data)."');");
    }
}

?>