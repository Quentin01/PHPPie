<?php
require_once "index.php";
use PHPPie\File\JSON as JSONFile;

$file = JSONFile::create(__DIR__.'/fichier.json');
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
