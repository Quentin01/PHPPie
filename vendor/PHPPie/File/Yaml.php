<?php

/*
 * Class Yaml files manager
 * Created on 23/10/11 at 15:53
 */

namespace PHPPie\File;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class Yaml extends File {
    public function __construct($pathFile)
    {
        parent::__construct($pathFile);
        
        if($this->getExtension() != 'yml')
        {
            throw new \Exception('File '.$this->pathFile.' isn\'t a Yaml\' file');
        }
    }
    
    public static function create($pathFile)
    {
        $file = parent::create($pathFile);
        
        if($file->getExtension() != 'yml')
        {
            $file->del();
            throw new \Exception('File '.$file->pathFile.' isn\'t a Yaml\' file');
        }
        
        return $file;
    }
    
    public function readData()
    {
        $yaml = new Parser();
        return $yaml->parse($this->getContents());
    }
    
    public function writeData($data)
    {
        $dumper = new Dumper();
        return $this->setContents($dumper->dump($data));
    }
}

?>