<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Configuration\Feature;

class FooControllerFeatureAtStaticMethod
{
    /**
     * @Feature("feature")
     */
    public static function barAction()
    {
    }
}
