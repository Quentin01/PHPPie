<?php
require 'vendor/PHPPie/Autoload/Autoloader.php';

$autoloader = new PHPPie\Autoload\Autoloader();

$autoloader->registerNamespaces(array(
    'PHPPie'           => __DIR__.'/vendor/PHPPie',
    'Doctrine'         => __DIR__.'/vendor/Doctrine',
    'Symfony'          => __DIR__.'/vendor/Doctrine/Symfony',
));

$autoloader->registerPrefixes(array(
    'Twig_'            => __DIR__.'/vendor/Twig',
));

$autoloader->registerNamespaceFallbacks(array(
    __DIR__.'/vendor',
));

$autoloader->register();

$kernel = new PHPPie\Core\Kernel(__DIR__, $autoloader, true);
$kernel->run();
?>
