<?php
require_once "index.php";
use PHPPie\File\Json as JsonFile;
use PHPPie\File\XML as XMLFile;
use PHPFile\Exception as Exception;

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

try{
$xml = new SimpleXMLElement($string);
}
catch(Exception $e)
{
	echo $e;
}

//print_r($xml);

$file = XMLFile::create(__DIR__.'/fichier.php');

$file->writeData($xml);
print_r($file->readData());

$file->del();
?>
