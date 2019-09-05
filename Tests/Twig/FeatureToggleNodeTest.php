<?php

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
use Twig\Test\NodeTestCase;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Node\Expression\NameExpression;
/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FeatureToggleNodeTest extends NodeTestCase
{
    public function getTests()
    {
        $tests = [];

        $feature = new Node([
            new PrintNode(new NameExpression('foo', 1), 1),
        ], array(), 1);

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
