<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Attributes\Feature;

class FooControllerFeatureAtInvoke
{
    #[Feature(name: 'feature')]
    public function __invoke()
    {
    }
}
