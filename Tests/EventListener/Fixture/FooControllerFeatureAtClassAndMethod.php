<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Configuration\Feature;

/**
 * @Feature("feature")
 */
class FooControllerFeatureAtClassAndMethod
{
    /**
     * @Feature("feature")
     */
    public function barAction()
    {
    }
}
