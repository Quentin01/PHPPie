<?php
include 'index.php';
$router = $kernel->container->getService('router');

$uri = '/blog/45';
if(false !== $route = $router->resolve($uri))
{
    echo 'Route trouvé : '.serialize($route->getParameters($uri));
}
else
{
    echo 'Route non trouvé';
}
?>
