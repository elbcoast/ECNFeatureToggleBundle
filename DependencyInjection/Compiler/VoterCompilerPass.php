<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class VoterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ecn_featuretoggle.voter_registry')) {
            return;
        }

        $definition = $container->getDefinition(
            'ecn_featuretoggle.voter_registry'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'ecn_featuretoggle.voter'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addVoter',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}
