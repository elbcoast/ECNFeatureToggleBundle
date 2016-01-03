<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Configuration\Feature;

class FooControllerFeatureAtMethod
{
    /**
     * @Feature("feature")
     */
    public function barAction()
    {
    }
}
