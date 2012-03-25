<?php

/*
 * Class route manager
 * Created on 26/10/2011 at 17:09
 */

namespace PHPPie\Routing;

class Route {
	public $router;
    public $pattern;
    public $defaults;
    public $requirements;
    
    public $patternRegexp;
    public $tokens        = array();
    protected $defaultURI = null;
    
    public function __construct(\PHPPie\Routing\Router $router, $pattern, array $defaults, array $requirements = array(), $patternRegexp = null, $tokens = null)
    {
		$this->router = $router;
        $this->pattern = $pattern;
        $this->defaults = $defaults;
        $this->requirements = $requirements;
        
        if(is_null($patternRegexp) && is_null($tokens))
        {
			$this->patternRegexp = $this->createRegexp();
		}
		else
		{
			$this->patternRegexp = $patternRegexp;
			$this->tokens = $tokens;
		}
    }
    
    protected function createRegexp()
    {
        $tokens = array();
        $pattern = preg_quote($this->pattern);
        preg_match_all('#(.?)(\{([a-zA-Z0-9]+)\})#', $this->pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        
        foreach($matches as $match)
        {     
            $requirement = isset($this->requirements[$match[3][0]]) ? $this->requirements[$match[3][0]] : '[a-zA-Z.-]+';
            $this->tokens[] = $match[3][0];
            
            if(!isset($this->defaults[$match[3][0]]))
            {
                $pattern = str_replace('\\{'.$match[3][0].'\\}', '(?P<'.$match[3][0].'>'.$requirement.')', $pattern);
            }
            else
            {
				echo $this->pattern . "<br/>";
                if($match[0][1] == 0 && !in_array(@$this->pattern[($match[0][1] + strlen($match[0][0]))], array('/', '\\', '.', '-')))
                    $pattern = str_replace(preg_quote($match[1][0]).'\\{'.$match[3][0].'\\}', preg_quote($match[1][0]).'(?P<'.$match[3][0].'>'.$requirement.')?', $pattern);
                else
                    $pattern = str_replace(preg_quote($match[1][0]).'\\{'.$match[3][0].'\\}', '('.preg_quote($match[1][0]).'(?P<'.$match[3][0].'>'.$requirement.')?)?', $pattern);
            }
        }
        
        if(substr($pattern, 0, 1) == "/")
			$pattern = "/?" . substr($pattern, 1);
		else
			$pattern = "/?" . $pattern;  
        
        return $pattern;
    }
    
    public function check($uri = null)
    {
        if(is_null($uri))
            $uri = $this->defaultURI;
        
        return preg_match('#^'.$this->patternRegexp.'$#i', $uri);
    }
    
    public function getParameters($uri = null)
    {
        if(is_null($uri))
            $uri = $this->defaultURI;
        
        $parameters = array();
        
        $result = preg_match('#^'.$this->patternRegexp.'$#i', $uri, $matches);
        
        if($result === false)
            throw new \PHPPie\Exception\Exception('The URI '.$uri.' aren\'t parameters with this route.', 'PHPPie\Routing\Route', 'getParameters');
        
        foreach($this->tokens as $token)
        {
            if(isset($matches[$token]))
            {
                if(@$matches[$token][0] === '/')
                    $matches[$token] = substr($matches[$token], 1);
                
                $parameters[$token] = $matches[$token];
            }
            else
            {
                $parameters[$token] = $this->defaults[$token];
            }
        }
        
        foreach($this->defaults as $name => $defaut)
        {
            if(!isset($parameters[$name]))
                $parameters[$name] = $defaut;
        }
        
        return $parameters;
    }
    
    public function setDefaultURI($uri)
    {
        $this->defaultURI = $uri;
    }
    
    public function getURI($slugs = array())
    {
		$uri = $this->router->kernel->container->getService('http.request')->getCompletURI() . $this->pattern;
		preg_match_all('#\{([a-zA-Z0-9]+)\}#', $uri, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		
		foreach($matches as $match)
		{
			if(isset($slugs[$match[1][0]]))
				$uri = str_replace($match[0][0], $slugs[$match[1][0]], $uri);
			else
				$uri = str_replace($match[0][0], "", $uri);
		}
		
		return str_replace(':/', '://', str_replace('//', '/', $uri));
	}
}
?>
