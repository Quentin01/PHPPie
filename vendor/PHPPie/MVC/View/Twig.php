<?php

/*
 * Twig View
 * Created on 10/03/12 at 19:30
 */

namespace PHPPie\MVC\View;

class Twig extends \PHPPie\MVC\View {
	protected static $loaderFilesystem = null;
	protected static $environment = null;
	
	public function __construct(\PHPPie\Core\KernelInterface $kernel, $pathfile = null)
	{
		parent::__construct($kernel, $pathfile);
		
		if(is_null(self::$environment))
			self::initialize();
	}
	
	protected static function initialize()
	{
		set_exception_handler(array(__CLASS__, 'exceptionHandler'));
		
		self::$loaderFilesystem = new \Twig_Loader_Filesystem(self::$kernel->getPathViews());
		self::$environment = new \Twig_Environment(self::$loaderFilesystem, array(
			'cache' => (!self::$kernel->debug) ? self::$kernel->getPathCache() : false,
			'debug' => self::$kernel->debug
		));
		
		self::$environment->addExtension(new Twig\Extension(self::$kernel));
		self::$environment->addTokenParser(new Twig\RenderTokenParser(self::$kernel));
	}

	public function getExtensionFile()
	{
		return '.twig';
	}

	public function render()
	{
		try {
			$template = self::$environment->loadTemplate($this->pathFile . $this->getExtensionFile());
			return $template->render($this->variables); 
		}
		catch(\Twig_Error $e) {
			static::exceptionHandler($e);
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
