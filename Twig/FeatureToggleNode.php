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
class FeatureToggleNode extends \Twig_Node
{
    public function __construct($name, \Twig_NodeInterface $feature, $lineno, $tag = null)
    {
        parent::__construct(['feature' => $feature], ['name' => $name], $lineno, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $name = $this->getAttribute('name');

        $compiler
            ->addDebugInfo($this)
            ->write(sprintf(
                'if ($this->env->getExtension(\'feature_toggle\')->getFeatureService()->has(\'%s\')) {',
                $name
            ))
            ->raw("\n")
            ->indent()
            ->subcompile($this->getNode('feature'))
            ->outdent()
            ->write('}')
        ;
    }
}
