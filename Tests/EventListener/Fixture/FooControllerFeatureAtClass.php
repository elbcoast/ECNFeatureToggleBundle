<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Configuration\Feature;

/**
 * @Feature("feature")
 */
class FooControllerFeatureAtClass
{
    public function barAction()
    {
    }
}
