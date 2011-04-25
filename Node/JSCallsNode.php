<?php

namespace Foliofarm\TwigBundle\Node;

class JSCallsNode extends \Twig_Node
{
    public function __construct($lineno, $tag = null)
    {
        parent::__construct(array(), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("echo \$this->env->getExtension('foliofarm')->renderJS();\n")
        ;
    }

}