<?php

/*
 * Twig Render Token Parser
 * Created on 11/03/12 at 16:30
 */

namespace PHPPie\MVC\View\Twig;

class RenderTokenParser extends \Twig_TokenParser {
	public function __construct()
	{
		
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
			$string .= ':' . $this->parser->getStream()->expect(\Twig_Token::NAME_TYPE)->getValue();
		}
		
		$this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
		
		return new RenderNode($string, $lineno, $this->getTag());
	}
}
