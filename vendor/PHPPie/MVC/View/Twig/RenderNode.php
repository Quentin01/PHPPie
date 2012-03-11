<?php

/*
 * Twig Render Node
 * Created on 11/03/12 at 16:30
 */
 
namespace PHPPie\MVC\View\Twig;

class RenderNode extends \Twig_Node implements \Twig_NodeOutputInterface {
    public function __construct($string, $lineno, $tag)
    {
        parent::__construct(array(), array('string' => $string), $lineno, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
		$compiler->addDebugInfo($this);
		$compiler->write('echo $this->env->getExtension("PHPPie_Extension")->functionRender(')
		         ->string($this->getAttribute('string'))
		         ->write(');');
    }
}
