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

$autoloader->registerDirectories(array(
	__DIR__.'/app/controllers',
    __DIR__.'/app/models',
));



$autoloader->register();

$cacheManager = new PHPPie\Cache\PHP(__DIR__.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'cache');
$autoloader->enableCache($cacheManager);

$uri = dirname(DIRECTORY_SEPARATOR . array_search('', $_GET));

$kernel = new PHPPie\Core\Kernel(__DIR__, $uri, $autoloader, $cacheManager, true);
$kernel->run();

$autoloader->save();
?>
