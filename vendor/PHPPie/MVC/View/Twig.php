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
		set_exception_handler(array(__CLASS__, 'exceptionHandler'));
		
		$this->loaderFilesystem = new \Twig_Loader_Filesystem($this->kernel->getPathViews());
		$this->environment = new \Twig_Environment($this->loaderFilesystem, array(
			'cache' => (!$this->kernel->debug) ? $this->kernel->getPathCache() : false,
			'debug' => $this->kernel->debug
		));
		
		$this->environment->addExtension(new Twig\Extension($this->kernel));
		$this->environment->addTokenParser(new Twig\RenderTokenParser($this->kernel));
	}

	public function getExtensionFile()
	{
		return '.twig';
	}

	public function render()
	{
		try {
		$template = $this->environment->loadTemplate($this->pathFile . $this->getExtensionFile());
		return $template->render($this->variables); 
	}
	catch(\Twig_Error $e)
	{
		echo $e;
	}
	}
	
	public static function exceptionHandler(\Twig_Error $e)
	{
		$exception = new \PHPPie\Exception\Exception((string) $e->getRawMessage());
		$exception->trace = $e->getTraceAsString();
		$exception->file = $e->getFile();
		$exception->line = $e->getLine();
		
		throw \PHPPie\Exception\Handler::exceptionHandler($exception);
	}
}
