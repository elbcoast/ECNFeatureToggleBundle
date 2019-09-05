<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle;

use Ecn\FeatureToggleBundle\DependencyInjection\Compiler\VoterCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class EcnFeatureToggleBundle extends Bundle
{
    /** @param ContainerBuilder $container */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new VoterCompilerPass());
    }
}
