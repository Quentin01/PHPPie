<?php

/*
 * Class PHP files manager
 * Created on 24/10/11 at 11:13
 */

namespace PHPPie\File;

class XML extends File {
    public function __construct($pathFile)
    {
        parent::__construct($pathFile);
        
        if($this->getExtension() != 'xml')
        {
            throw new \PHPPie\Exception\Exception('File '.$this->pathFile.' isn\'t a XML file');
        }
    }
    
    public static function create($pathFile)
    {
        return parent::create($pathFile);
    }

    public function readData()
    {
        return simplexml_load_string($this->getContents());
    }
    
    public function writeData($data)
    {
        if(is_object($data))
        {
            if(get_class($data) === "SimpleXMLElement")
                return $this->setContents($data->asXML());
            else
                throw new \PHPPie\Exception\Exception('Data isn\'t a SimpleXMLElement object');
        }
        else
        {
            throw new \PHPPie\Exception\Exception('Data isn\'t a object');
        }
    }
}

?>
