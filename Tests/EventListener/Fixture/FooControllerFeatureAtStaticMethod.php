<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Attributes\Feature;

class FooControllerFeatureAtStaticMethod
{
    #[Feature(name: 'feature')]
    public static function barAction()
    {
    }
}
