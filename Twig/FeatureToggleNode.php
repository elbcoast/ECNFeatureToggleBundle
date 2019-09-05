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

use Twig\Compiler;
use Twig\Node\Node;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FeatureToggleNode extends Node
{
    /**
     * FeatureToggleNode constructor.
     *
     * @param string      $name
     * @param Node        $feature
     * @param int         $lineno
     * @param string|null $tag
     */
    public function __construct(string $name, Node $feature, int $lineno = 0, string $tag = null)
    {
        parent::__construct(['feature' => $feature], ['name' => $name], $lineno, $tag);
    }

    /**
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler): void
    {
        $name = $this->getAttribute('name');

        $compiler
            ->addDebugInfo($this)
            ->write(sprintf(
                'if ($this->env->getExtension(\'Ecn\FeatureToggleBundle\Twig\FeatureToggleExtension\')->getFeatureService()->has(\'%s\')) {',
                $name
            ))
            ->raw("\n")
            ->indent()
            ->subcompile($this->getNode('feature'))
            ->outdent()
            ->write('}');
    }
}
