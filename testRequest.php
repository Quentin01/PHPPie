<?php
include 'index.php';
use PHPPie\HTTP\Request as Request;
//$requete = $kernel->container->getService('Request');
$requete = new Request();
//print_r($requete);
$requete->addFile(array('titre' => 'rien_du_tout'));
print_r($requete->getFile('titre'));
//echo $requete->getRequestUri();