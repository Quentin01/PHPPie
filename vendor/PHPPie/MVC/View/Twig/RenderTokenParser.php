<?php

/*
 * Twig Render Token Parser
 * Created on 11/03/12 at 16:30
 */

namespace PHPPie\MVC\View\Twig;

class RenderTokenParser extends \Twig_TokenParser {
	protected $kernel;
	
	public function __construct(\PHPPie\Core\KernelInterface $kernel)
	{
		$this->kernel = $kernel;
	}
	
	public function getTag()
	{
		return 'render';
	}
	
	public function parse(\Twig_Token $token)
	{
		$lineno = $token->getLine();
		
		$string = $this->parser->getStream()->expect(\Twig_Token::NAME_TYPE)->getValue();
		
		if($this->parser->getStream()->test(\Twig_Token::PUNCTUATION_TYPE, ':'))
		{
			$this->parser->getStream()->next();
			$this->parser->getStream()->expect(\Twig_Token::PUNCTUATION_TYPE, ':');
			$string .= ':' . $this->parser->getStream()->expect(\Twig_Token::NAME_TYPE)->getValue();
		}
		
		$this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
		
		return new RenderNode($string, $lineno, $this->getTag());
	}
}
