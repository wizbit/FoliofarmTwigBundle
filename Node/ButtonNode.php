<?php

namespace Foliofarm\TwigBundle\Node;

class ButtonNode extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $name, \Twig_Node_Expression $vars, $lineno, $tag = null)
    {
        parent::__construct(array('vars' => $vars, 'name' => $name), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $vars = $this->getNode('vars');
        $defaults = new \Twig_Node_Expression_Array(array(), -1);
        if ($vars instanceof \Twig_Node_Expression_Array) {
            $defaults = $this->getNode('vars');
            $vars = null;
        }    	
        
        $compiler
            ->addDebugInfo($this)
            ->raw('echo $this->env->getExtension(\'foliofarm\')->renderButton(')
            ->subcompile($this->getNode('name'))
            ->raw(', ');
            
        if (null !== $vars) {
            $compiler->raw('array_merge(');
            $this->compileDefaults($compiler, $defaults);
            $compiler
                ->raw(', ')
                ->subcompile($this->getNode('vars'))
                ->raw(')')
            ;
        } else {
            $this->compileDefaults($compiler, $defaults);
        }            

        $compiler->raw(");\n");
    }
    
    protected function compileDefaults(\Twig_Compiler $compiler, \Twig_Node_Expression_Array $defaults)
    {
    	$first = true;
        $compiler->raw('array(');
        foreach($defaults as $name => $default) {
        	if(!$first)
            	$compiler->raw(', ');
            	
        	$compiler
                ->repr($name)
                ->raw(' => ')
                ->subcompile($default)
            ;
            
            $first = false;
        }
        $compiler->raw(')');
    }    
}