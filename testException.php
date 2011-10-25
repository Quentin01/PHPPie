<?php
require_once "index.php";
use PHPPie\Exception\Exception as PHPPie_Exception;

try
{
    throw new PHPPie_Exception('Message');
}
catch(PHPPie_Exception $e)
{
    echo $e.'<br/>';
}

try
{
    throw new PHPPie_Exception('Message with class', 'PHPPie\\Class');
}
catch(PHPPie_Exception $e)
{
    echo $e.'<br/>';
}

try
{
    throw new PHPPie_Exception('Message with class and method', 'PHPPie\\Class', 'methodName');
}
catch(PHPPie_Exception $e)
{
    echo $e.'<br/>';
}
?>
