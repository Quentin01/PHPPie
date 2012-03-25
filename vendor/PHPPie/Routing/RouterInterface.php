<?php

/*
 * Interface of router
 * Created on 25/03/12 at 13:20
 */

namespace PHPPie\Routing;

interface RouterInterface {
    public function resolve($uri);
    public function getURI($name, $slugs = array());
}
?>
