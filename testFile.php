<?php
require_once "index.php";
use PHPPie\File\Json as JsonFile;
use PHPPie\File\Yaml as YamlFile;

$file = YamlFile::create(__DIR__.'/fichier.yml');
echo $file->getSize()."<br/>";

$array = array(
    'test' => array(
        'test', 'test'
    ),
    'test2' => 'e'
);

$file->writeData($array);
echo $file->getContents();

$file->del();
?>
