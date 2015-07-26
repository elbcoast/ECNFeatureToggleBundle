<?php

namespace Ecn\FeatureToggleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Ecn\FeatureToggleBundle\DependencyInjection\Compiler\VoterCompilerPass;

/**
 * Class EcnFeatureToggleBundle
 *
 * PHP Version 5.4
 *
 * @author Pierre Groth <pierre@elbcoast.net>
 * @license MIT
 *
 */
class EcnFeatureToggleBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new VoterCompilerPass());
    }
}