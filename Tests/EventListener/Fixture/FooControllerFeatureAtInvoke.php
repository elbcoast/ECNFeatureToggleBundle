<?php

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Configuration\Feature;


class FooControllerFeatureAtInvoke
{
    /**
     * @Feature("feature")
     */
    public function __invoke()
    {
    }
}
