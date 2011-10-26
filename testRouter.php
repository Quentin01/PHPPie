<?php
include 'index.php';
$router = $kernel->container->getService('router');

$uri = '/blog/45';
if(false !== $route = $router->resolve($uri))
{
    echo 'Route trouvé, paramètres : '.print_r($route->getParameters());
}
else
{
    echo 'Route non trouvé';
}
?>
