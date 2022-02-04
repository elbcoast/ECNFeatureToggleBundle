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

namespace Ecn\FeatureToggleBundle\Tests\Twig;

use Ecn\FeatureToggleBundle\Twig\FeatureToggleNode;
use Twig\Environment;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Test\NodeTestCase;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FeatureToggleNodeTest extends NodeTestCase
{
    /**
     * @dataProvider getTests
     *
     * @param FeatureToggleNode $node
     * @param string $source
     * @param Environment $environment
     * @param bool $isPattern
     */
    public function testCompile($node, $source, $environment = null, $isPattern = false)
    {
        parent::testCompile($node, $source, $environment = null, $isPattern = false);
    }

    public function getTests(): array
    {
        $tests = [];

        $feature = new Node([
            new PrintNode(new NameExpression('foo', 1), 1),
        ], [], 1);

        $node = new FeatureToggleNode('feature', $feature, 1);

        $tests[] = [$node, <<<EOF
// line 1
if (\$this->env->getExtension('Ecn\FeatureToggleBundle\Twig\FeatureToggleExtension')->getFeatureService()->has('feature')) {
    echo {$this->getVariableGetter('foo')};
}
EOF
        ];

        return $tests;
    }
}
