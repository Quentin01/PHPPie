<?php

/*
 * Class PHP files manager
 * Created on 24/10/11 at 11:13
 */

namespace PHPPie\File;

class PHP extends File {
    public function __construct($path, $create = false)
    {
        parent::__construct($path, $create);
        
        if($this->getExtension() != 'php' && $this->getExtension() != 'php5')
        {
            throw new \PHPPie\Exception\Exception('File '.$this->path.' isn\'t a PHP file');
        }
    }
    
    public function readData()
    {
        include $this->path;
        return $data;
    }
    
    public function writeData($data)
    {
        return $this->setContents("<?php \$data = unserialize('".serialize($data)."');");
    }
}

?>
