<?php
namespace Ecn\FeatureToggleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

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