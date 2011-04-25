<?php

namespace Foliofarm\TwigBundle\Extension;

use Foliofarm\TwigBundle\TokenParser\JSCallsTokenParser;
use Foliofarm\TwigBundle\TokenParser\JSCallTokenParser;
use Foliofarm\TwigBundle\TokenParser\ButtonTokenParser;

/**
 *
 * @author Marcus Speight
 */
class TwigExtension extends \Twig_Extension
{
	private $jsCalls = array();
	private $menus = null;
	
    public function getTokenParsers()
    {
        return array(
            // {% button 'Delete' with { 'url': 2 } %}
            new ButtonTokenParser(),
            
            // {% js_call '$(?).hide()' with ['#content'] %}
            new JSCallTokenParser(),
            
            // {% js_calls %}
            new JSCallsTokenParser(),
        );    	
    }
    
    private function entities($s)
    {
    	return htmlentities($s, ENT_COMPAT, 'UTF-8'); 
    }
    
    public function renderButton($label, array $parameters = array())
    {
   		$classes = isset($parameters['classes']) ? (array)$parameters['classes'] : array();
   		$url = isset($parameters['url']) ? $parameters['url'] : '#';
    	
    	$hasIcon = in_array('icon', $classes);
    	$link = in_array('ff-link', $classes);
    	$arrowBtn = in_array('ff-arrow-btn', $classes);
    	
    	// if it is an ff-events button, grab the date and append it as a class
    	if(in_array('ff-events', $classes))
    		$classes[] = 'day-'.date('j');
    		
    	$s = '<a ';
    	
    	if(isset($parameters['name']))
    		$s .= 'name="' . $this->entities($parameters['name']) . '" ';

    	if(isset($parameters['title']))
    		$s .= 'title="' . $this->entities($parameters['title']) . '" ';
    		
    	$s .= 'href="' . $this->entities($url) . '" ';
    		
    	if(isset($parameters['id']))
    		$s .= 'id="' . $this->entities($parameters['id']) . '" ';
    		    		
    	if(!$link && !$arrowBtn)
    		$s .= 'class="ff-btn ' . implode(' ', $classes) . '">';
    	else
    		$s .= 'class="' . implode(' ', $classes) . '">';
    		
    	if($hasIcon)
    		$s .= '<span class="icon"></span>';
    		
    	$s .= $label . '</a>';
    	
		return $s;    	
    }
    
    public function addJS($expr, $attributes = array())
    {
    	$this->jsCalls[] = array('callable' => $expr, 'args' => $attributes);
    }
    
    public function renderJS()
    {
    	$html = '';
    	
    	if(count($this->jsCalls))
    	{
    		$html .= '<script type="text/javascript" charset="utf-8">'."\n";
    		$html .= 'jQuery(function($){'."\n";
    		
    		foreach($this->jsCalls as $call)
    		{
    			$clauses = preg_split('/(\?)/', $call['callable'], null, PREG_SPLIT_DELIM_CAPTURE);
    			$code = '';
    			$n = 0;
    			$q = 0;

    			foreach($clauses as $clause)
    			{
    				if($clause === '?')
    					$code .= json_encode($call['args'][$n++]);
    				else
    					$code .= $clause;
    			}
    			
    			if($n !== count($call['args']))
    				throw new \Exception('Number of arguments does not match number of ? placeholders in js call');
    			
    			$html .= $code . ";\n";
    		}
    		
    		$html .= "});\n";
    		$html .= "</script>\n";
    	}
    	
    	return $html;
    }

    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'foliofarm';
    }
}