<?php
declare(strict_types=1);

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Twig;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @psalm-suppress UndefinedClass
 */
class FeatureToggleTokenParser extends AbstractTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Token $token): FeatureToggleNode
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $name = $stream->expect(Token::NAME_TYPE)->getValue();
        $stream->expect(Token::BLOCK_END_TYPE);
        $feature = $this->parser->subparse([$this, 'decideFeatureEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new FeatureToggleNode($name, $feature, $lineno, $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag(): string
    {
        return 'feature';
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function decideFeatureEnd(Token $token): bool
    {
        return $token->test('endfeature');
    }
}
