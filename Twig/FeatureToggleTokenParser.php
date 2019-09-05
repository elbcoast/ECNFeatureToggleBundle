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

use Twig\TokenParser\AbstractTokenParser;
use Twig\Token;
/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FeatureToggleTokenParser extends AbstractTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $name = $stream->expect(Token::NAME_TYPE)->getValue();
        $stream->expect(Token::BLOCK_END_TYPE);
        $feature = $this->parser->subparse([$this, 'decideFeatureEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new FeatureToggleNode($name, $feature, $lineno, $this->getTag());
    }

    public function decideFeatureEnd(Token $token)
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
