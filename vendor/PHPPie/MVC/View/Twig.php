<?php

/*
 * Twig View
 * Created on 10/03/12 at 19:30
 */

namespace PHPPie\MVC\View;

class Twig extends \PHPPie\MVC\View {
	protected $loaderFilesystem = null;
	protected $environment = null;
	
	public function __construct(\PHPPie\Core\KernelInterface $kernel, $pathfile = null)
	{
		parent::__construct($kernel, $pathfile);
		
		$this->loaderFilesystem = new \Twig_Loader_Filesystem($this->kernel->getPathViews());
		$this->environment = new \Twig_Environment($this->loaderFilesystem, array(
			'cache' => (!$this->kernel->debug) ? $this->kernel->getPathCache() : false,
		));
	}

	public function getRealPathfile()
	{
		return $this->kernel->getPathViews() . DIRECTORY_SEPARATOR . $this->pathFile . '.twig';
	}

	public function render()
	{
		$template = $this->environment->loadTemplate($this->pathFile . '.twig');
		return $template->render($this->variables);
	}
	
}
