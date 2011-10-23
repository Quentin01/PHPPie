<?php
require_once('File.php');


$fichier = new File('test2.php');

$fichier->copy('test_cy.php');
$fichier->chmod(0777);
echo $fichier->isWritable();
echo $fichier->isReadable();