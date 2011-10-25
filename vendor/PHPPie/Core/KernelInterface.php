<?php

/*
 * Core class of Framework
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;

interface KernelInterface {
    public function run();
    
    public function getPathApp();
    public function getPathWeb();
    public function getPathCache();
    public function getPathConfig();
    public function getPathControllers();
    public function getPathModels();
    public function getPathViews();
    public function getPathCss();
    public function getPathImages();
    public function getPathJs();
}
?>
