<?php
require_once "index.php";
use PHPPie\File\File;

$file = File::create(__DIR__.'/fichier.txt');
echo $file->getDir();
?>
