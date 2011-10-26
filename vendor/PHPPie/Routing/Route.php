<?php

/*
 * Class route manager
 * Created on 26/10/2011 at 17:09
 */

namespace PHPPie\Routing;

class Route {
    protected $pattern;
    protected $defaults;
    protected $requirements;
    protected $tokens = array();
    
    protected $defaultURI = null;
    
    public function __construct($pattern, array $defaults, array $requirements = array())
    {
        $this->pattern = $pattern;
        $this->defaults = $defaults;
        $this->requirements = $requirements;
        
        $this->patternRegexp = $this->createRegexp();
    }
    
    protected function createRegexp()
    {
        $tokens = array();
        $pattern = preg_quote($this->pattern);
        preg_match_all('#(-|\/|\.)(\{([a-zA-Z]+)\})#', $this->pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        
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
                if($match[0][1] == 0 && @$this->pattern[($match[0][1] + strlen($match[0][0]))] !== '/')
                    $pattern = str_replace(preg_quote($match[1][0]).'\\{'.$match[3][0].'\\}', preg_quote($match[1][0]).'(?P<'.$match[3][0].'>'.$requirement.')?', $pattern);
                else
                    $pattern = str_replace(preg_quote($match[1][0]).'\\{'.$match[3][0].'\\}', '('.preg_quote($match[1][0]).'(?P<'.$match[3][0].'>'.$requirement.'))?', $pattern);
            }
        }
        
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
            throw new \PHPPie\Exception\Exception('The URI '.$uri.' not are parameters with this route.', 'PHPPie\Routing\Route', 'getParameters');
        
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
}
?>
