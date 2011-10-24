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
            throw new \Exception('File '.$this->pathFile.' isn\'t a XML\' file');
        }
    }
    
    public static function create($pathFile)
    {
        $file = parent::create($pathFile);
        
        if($file->getExtension() != 'xml')
        {
            $file->del();
            throw new \Exception('File '.$file->pathFile.' isn\'t a XML\' file');
        }
        
        return $file;
    }

    /**
     * Convertit un fichier XML en objet SimpleXML.
     * @return object SimpleXML
     */
    public function readData()
    {
        return simplexml_load_string($this->getContents());
    }
    
    /**
     * convertit un objet SimpleXMLElement en fichier XML.
     * @param object Object SimpleXMLElement
     * @return bool True si ça a marché ou False si ça n'a pas marché.
     */
    public function writeData($data)
    {
        if(is_object($data))
        {
            if(get_class($data) === "SimpleXMLElement")
                return $this->setContents($data->asXML());
            else
                throw new \Exception('Data isn\'t a SimpleXMLElement object');
        }
        else
        {
            throw new \Exception('Data isn\'t a object');
        }
    }
}

?>