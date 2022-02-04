<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Attributes\Feature;

#[Feature(name: 'feature')]
class FooControllerFeatureAtClass
{
    public function barAction()
    {
    }
}
