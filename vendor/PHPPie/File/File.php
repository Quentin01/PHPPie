<?php

/*
 * Class files manager
 * Create on 23/10/11 at 09:34
 */

namespace PHPPie\File;

abstract class File {

    protected $name;
    protected $dir;
    protected $pathFile;

    public function __construct($pathFile) {
        $this->pathFile = realpath($pathFile);

        if (!$this->exists())
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');

        $this->name = basename($this->pathFile);
        $this->dir = $this->getDir($this->pathFile);
    }

    public static function create($pathFile) {
        if (!is_dir(dirname($pathFile))) {
            if (!mkdir(dirname($pathFile), 0777, true))
                throw new \Exception('Permission denied to create directory : ' . dirname($pathFile));
        }
        
        if(file_put_contents($pathFile, '') !== false)
        {
            $file = new static($pathFile);
            $file->chmod(0777);
            return $file;
        }
        else
        {
            throw new \Exception('Permission denied to create file : ' . $pathFile);
        }
    }

    public function exists($pathFile = null) {
        if (is_null($pathFile))
            return file_exists($this->pathFile);
        else
            return file_exists($pathFile);
    }

    public function getSize($pathFile = null) {
        if (is_null($pathFile))
            return filesize($this->pathFile);
        elseif ($this->exists($pathFile))
            return filesize($pathFile);
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }

    public function getDir($pathFile = null) {
        if (is_null($pathFile))
            return $this->dir;
        elseif ($this->exists($pathFile))
            return realpath(dirname($pathFile));
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }
    
    public function getPath()
    {
        return $this->pathFile;
    }
    
    public function getName($pathFile = null)
    {
        if (is_null($pathFile))
            return $this->name;
        elseif ($this->exists($pathFile))
            return basename($pathFile);
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }
    
    public function getExtension($pathFile = null)
    {
        if (is_null($pathFile))
        {
            $extension = explode('.', $this->name);
            return $extension[1];
        }
        elseif ($this->exists($pathFile))
        {
            $extension = explode('.', $this->getName($pathFile));
            return $extension[1];
        }
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }
    
    public function getContents($pathFile = null)
    {
        if (is_null($pathFile))
            return file_get_contents($this->pathFile);
        elseif ($this->exists($pathFile))
            return file_get_contents($pathFile);
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }
    
    public function setContents($contents, $pathFile = null)
    {
        if (is_null($pathFile))
            return (file_put_contents($this->pathFile, $contents) !== false);
        elseif ($this->exists($pathFile))
            return (file_put_contents($pathFile, $contents) !== false);
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }

    public function del($pathFile = null) {
        if (is_null($pathFile)) {
            return $this->del($this->pathFile);
        } elseif ($this->exists($pathFile)) {
            if (unlink($pathFile))
                return true;
            else
                throw new \Exception('Permission denied to delete file : ' . $pathFile);
        }
        else {
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
        }
    }

    public function rename($name, $pathFile = null) {
        if (is_null($pathFile)) {
            $name = $this->dir . DIRECTORY_SEPARATOR . $name;

            if (rename($this->pathFile, $name)) {
                $this->pathFile = $name;
                $this->name = $name;
                $this->dir = $this->getDir();

                return true;
            } else {
                throw new \Exception('Permission denied to rename file : ' . $this->pathFile);
            }
        } elseif ($this->exists($pathFile)) {
            $name = $this->getDir($pathFile) . DIRECTORY_SEPARATOR . $name;

            if (rename($pathFile, $name))
                return true;
            else
                throw new \Exception('Permission denied to rename file : ' . $pathFile);
        }
        else {
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
        }
    }

    public function move($path, $pathFile = null) {
        if (is_null($pathFile)) {
            if ($this->copy($path)) {
                $this->del($this->pathFile);

                $this->pathFile = $path;
                $this->name = basename($path);
                $this->dir = $this->getDir();

                return true;
            }
        } elseif ($this->exists($pathFile)) {
            if ($this->copy($path, $pathFile))
                return $this->del($pathFile);
        }
        else {
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
        }
    }

    public function copy($path, $pathFile = null) {
        if (!is_dir(dirname($path))) {
            if (!mkdir(dirname($path), 0777, true))
                throw new \Exception('Permission denied to create directory : ' . dirname($path));
        }

        if (is_null($pathFile)) {
            if (copy($this->pathFile, $path))
                return true;
            else
                throw new \Exception('Permission denied to copy file ' . $this->pathFile . ' in ' . $path);
        }
        elseif ($this->exists($pathFile)) {
            if (copy($pathFile, $path))
                return true;
            else
                throw new \Exception('Permission denied to copy file ' . $pathFile . ' in ' . $path);
        }
        else {
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
        }
    }

    public function chmod($chmod, $pathFile = null) {
        if (is_int($chmod)) {
            if (is_null($pathFile)) {
                if (chmod($this->pathFile, $chmod))
                    return true;
                else
                    throw new \Exception('Permission denied to edit chmod\'s file ' . $this->pathFile);
            }
            elseif ($this->exists($pathFile)) {
                if (chmod($pathFile, $chmod))
                    return true;
                else
                    throw new \Exception('Permission denied to edit chmod\'s file ' . $pathFile);
            }
            else {
                throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
            }
        } else {
            return false;
        }
    }

    public function isWritable($pathFile = null) {
        if (is_null($pathFile))
            return is_writable($this->pathFile);
        elseif ($this->exists($pathFile))
            return is_writable($pathFile);
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }

    public function isReadable($pathFile = null) {
        if (is_null($pathFile))
            return is_readable($this->pathFile);
        elseif ($this->exists($pathFile))
            return is_readable($pathFile);
        else
            throw new \Exception('File ' . $pathFile . ' doesn\'t exists');
    }
    
    public function __tostring()
    {
        return $this->getPath();
    }

    abstract public function readArray();
    abstract public function writeArray(array $array);
}

?>