<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Attributes\Feature;

class FooControllerFeatureAtMethod
{
    #[Feature(name: 'feature')]
    public function barAction()
    {
    }
}
