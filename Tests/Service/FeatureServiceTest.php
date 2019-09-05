<?php
declare(strict_types=1);

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Tests\Service;

use Ecn\FeatureToggleBundle\Service\FeatureService;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;
use PHPUnit\Framework\TestCase;
use Ecn\FeatureToggleBundle\Voters\VoterInterface;
/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class FeatureServiceTest extends TestCase
{
    /**
     * Test new feature
     */
    public function testIfFeatureMatches(): void
    {

        // Define a feature
        $features = [
            'testfeature' => [
                'voter' => 'AlwaysTrueVoter',
                'params' => []
            ]
        ];
        $default = [
            'voter' => null,
            'params' => []
        ];

        // Create service
        $service = new FeatureService($features, $default, $this->getRegistry());

        $this->assertTrue($service->has('testfeature'));
        $this->assertFalse($service->has('unknownfeature'));
    }

    /**
     * Test Default voter
     */
    public function testDefaultVoter(): void
    {

        // Define a feature
        $features = [
            'testfeature' => [
                'voter' => null,
                'params' => []
            ]
        ];
        $default = [
            'voter' => 'AlwaysTrueVoter',
            'params' => []
        ];

        // Create service
        $service = new FeatureService($features, $default, $this->getRegistry());

        $this->assertTrue($service->has('testfeature'));
        $this->assertFalse($service->has('unknownfeature'));
    }

    /**
     * @return VoterRegistry
     */
    protected function getRegistry(): VoterRegistry
    {

        // Create alwaysTrueVoter stub
        $alwaysTrueVoter = $this->createMock(VoterInterface::class);
        $alwaysTrueVoter
            ->method('pass')
            ->willReturn(true);

        // Create alwaysFalseVoter stub
        $alwaysFalseVoter = $this->createMock(VoterInterface::class);
        $alwaysFalseVoter
            ->method('pass')
            ->willReturn(false);

        // Create a registry with the voter stubs in it
        $registry = new VoterRegistry();
        $registry->addVoter($alwaysTrueVoter, 'AlwaysTrueVoter');
        $registry->addVoter($alwaysFalseVoter, 'AlwaysFalseVoter');

        return $registry;
    }
}
