<?php
require_once "index.php";
use PHPPie\File\Json as JsonFile;
use PHPPie\File\XML as XMLFile;

$string = <<<XML
<a>
 <b>
  <c>text</c>
  <c>stuff</c>
 </b>
 <d>
  <c>code</c>
 </d>
</a>
XML;

$xml = new SimpleXMLElement($string);
//print_r($xml);

$file = XMLFile::create(__DIR__.'/fichier.xml');

$file->writeData($xml);
print_r($file->readData());

$file->del();
?>
