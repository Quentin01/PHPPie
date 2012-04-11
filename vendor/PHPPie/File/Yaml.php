<?php

/*
 * Class Yaml files manager
 * Created on 23/10/11 at 15:53
 */

namespace PHPPie\File;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class Yaml extends File {
    public function __construct($path, $create = false)
    {
        parent::__construct($path, $create);
        
        if($this->getExtension() != 'yml')
        {
            throw new \PHPPie\Exception\Exception('File '.$this->path.' isn\'t a Yaml file');
        }
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
