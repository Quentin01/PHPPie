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
     * @param object SimpleXMLElement SimpleXMLElement
     * @return bool True si ça a marché ou False si ça n'a pas marché.
     */
    public function writeData(SimpleXMLElement $data)
    {
        return $this->setContents($data->asXML());
    }
}

?>