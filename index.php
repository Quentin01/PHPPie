<?php
require 'vendor/PHPPie/Autoload/Autoloader.php';

$autoloader = new PHPPie\Autoload\Autoloader();

$autoloader->registerNamespaces(array(
    'PHPPie' => __DIR__.'/vendor/PHPPie',
    'Doctrine' => __DIR__.'/vendor/Doctrine',
));

$autoloader->registerPrefixes(array(
    'Twig_'            => __DIR__.'/vendor/Twig',
));

$autoloader->registerNamespaceFallbacks(array(
    __DIR__.'/vendor',
));

$autoloader->register();
?>