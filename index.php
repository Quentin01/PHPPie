<?php
session_start();
require 'vendor/PHPPie/Autoload/Autoloader.php';

$autoloader = new PHPPie\Autoload\Autoloader();

$autoloader->registerNamespaces(array(
    'PHPPie'           => __DIR__.'/vendor/PHPPie',
    'Doctrine'         => __DIR__.'/vendor/Doctrine',
    'Symfony'          => __DIR__.'/vendor/Doctrine/Symfony',
    'Entities'          => __DIR__.'/app/models/Entities',
    'Proxy'          => __DIR__.'/app/models/Proxy',
));

$autoloader->registerPrefixes(array(
    'Twig_'            => __DIR__.'/vendor/Twig',
));

$autoloader->registerNamespaceFallbacks(array(
    __DIR__.'/vendor',
));

$autoloader->registerDirectories(array(
	__DIR__.'/vendor/jsxs',
	__DIR__.'/app/controllers',
	__DIR__.'/vendor/PHPPie/MVC/DefaultControllers',
    __DIR__.'/app/models',
));

$autoloader->register();

$cacheManager = new PHPPie\Cache\PHP(__DIR__.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'cache');
$autoloader->enableCache($cacheManager);

$kernel = new PHPPie\Core\Kernel(__DIR__, $autoloader, $cacheManager, true);

\PHPPie\Event\Handler::attach(new \PHPPie\Event\Listeners\Assets());

$firewall = new \PHPPie\Event\Listeners\Firewall();

/*$firewall->setRoute('hello*', function() { return true; }, array(
	'controller' => function() { return (!isset($_SESSION['id'])) ? 'Controller:test' : 'Controller:index'; },
));*/

\PHPPie\Event\Handler::attach($firewall);

$kernel->run();
$autoloader->save();
?>
