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

use Foliofarm\TwigBundle\Node\JSCallsNode;

/**
 * @author Marcus Speight
 */
class JSCallsTokenParser extends \Twig_TokenParser
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
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        
        return new JSCallsNode($token->getLine(), $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @param string The tag name
     */
    public function getTag()
    {
        return 'js_calls';
    }
}