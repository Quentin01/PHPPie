<?php
require_once "index.php";
use PHPPie\File\File;

$file = new File(__DIR__.'/fichier.txt');
$file->rename('fichier2.txt');
?>
