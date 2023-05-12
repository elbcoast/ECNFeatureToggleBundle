<?php declare(strict_types=1);

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Tests;

use Ecn\FeatureToggleBundle\DependencyInjection\EcnFeatureToggleExtension;
use Ecn\FeatureToggleBundle\EcnFeatureToggleBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class EcnFeatureToggleBundleTest extends TestCase
{
    private ContainerBuilder $configuration;

    /**
     * Test if the default configuration for a feature
     * contains the default settings for voter and params.
     *
     * @throws \Exception
     */
    public function testDefaultSettings(): void
    {
        $this->createConfiguration([
            'features' => ['testFeature' => []],
        ]);

        // Load feature config
        $features = $this->configuration->getParameter('features');
        $default = $this->configuration->getParameter('default');

        $this->assertSame(['voter' => 'AlwaysTrueVoter', 'params' => []], $default);
        $this->assertSame([], $features['testFeature']['params']);
    }

    /**
     * @throws \Exception
     */
    private function createConfiguration(array $config = []): void
    {
        $this->configuration = new ContainerBuilder();

        $bundle = new EcnFeatureToggleBundle();
        $bundle->build($this->configuration);

        $loader = new EcnFeatureToggleExtension();
        $loader->load([$config], $this->configuration);
        $this->assertInstanceOf(ContainerBuilder::class, $this->configuration);
    }

    protected function tearDown(): void
    {
        unset($this->configuration);
    }
}
