<?php
require_once "index.php";
//use PHPPie\File as JSONFile;
use PHPPie\File as YamlFile;

$file = YamlFile::create(__DIR__.'/fichier.yml');
echo $file->getSize()."<br/>";

$array = array(
    'test' => array(
        'test', 'test'
    ),
    'test2' => 'e'
);

$file->writeArray($array);
echo $file->getContents();

$file->del();
?>
