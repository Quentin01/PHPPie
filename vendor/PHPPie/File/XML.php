<?php

/*
 * Class PHP files manager
 * Created on 24/10/11 at 11:13
 */

namespace PHPPie\File;

class XML extends File {
    public function __construct($path, $create = false)
    {
        parent::__construct($path, $create);
        
        if($this->getExtension() != 'xml')
        {
            throw new \PHPPie\Exception\Exception('File '.$this->path.' isn\'t a XML file');
        }
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
