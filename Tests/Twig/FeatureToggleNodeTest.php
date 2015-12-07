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

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FeatureToggleNodeTest extends \Twig_Test_NodeTestCase
{
    public function getTests()
    {
        $tests = [];

        $feature = new \Twig_Node([
            new \Twig_Node_Print(new \Twig_Node_Expression_Name('foo', 1), 1),
        ], array(), 1);

        $node = new FeatureToggleNode('feature', $feature, 1);

        $tests[] = [$node, <<<EOF
// line 1
if (\$this->env->getExtension('feature_toggle')->getFeatureService()->has('feature')) {
    echo {$this->getVariableGetter('foo')};
}
EOF
        ];

        return $tests;
    }
}
