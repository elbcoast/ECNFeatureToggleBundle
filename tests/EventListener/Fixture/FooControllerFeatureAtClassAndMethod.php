<?php declare(strict_types=1);

namespace Ecn\FeatureToggleBundle\Tests\EventListener\Fixture;

use Ecn\FeatureToggleBundle\Attributes\Feature;

#[Feature(name: 'feature')]
class FooControllerFeatureAtClassAndMethod
{
    #[Feature(name: 'feature')]
    public function barAction()
    {
    }
}
