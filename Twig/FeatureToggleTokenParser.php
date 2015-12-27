<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Twig;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FeatureToggleTokenParser extends \Twig_TokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $name = $stream->expect(\Twig_Token::NAME_TYPE)->getValue();
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        $feature = $this->parser->subparse([$this, 'decideFeatureEnd'], true);
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new FeatureToggleNode($name, $feature, $lineno, $this->getTag());
    }

    public function decideFeatureEnd(\Twig_Token $token)
    {
        return $token->test('endfeature');
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'feature';
    }
}
