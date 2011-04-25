<?php

/*
 * This file is part of the Foliofarm package.
 *
 * (c) Marcus Speight
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Foliofarm\TwigBundle\TokenParser;

use Foliofarm\TwigBundle\Node\JSCallNode;

/**
 * @author Marcus Speight
 */
class JSCallTokenParser extends \Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A \Twig_Token instance
     *
     * @return \Twig_NodeInterface A \Twig_NodeInterface instance
     */
    public function parse(\Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        // attributes
        if ($this->parser->getStream()->test(\Twig_Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();

            $attributes = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $attributes = new \Twig_Node_Expression_Array(array(), $token->getLine());
        }
        
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        
        return new JSCallNode($expr, $attributes, $token->getLine());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @param string The tag name
     */
    public function getTag()
    {
        return 'js_call';
    }
}