<?php

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
    public function testIfFeatureMatches()
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

    public function testDefaultVoter()
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
    protected function getRegistry()
    {

        // Create alwaysTrueVoter stub
        $alwaysTrueVoter = $this->createMock(VoterInterface::class);
        $alwaysTrueVoter->expects($this->any())
            ->method('pass')
            ->will($this->returnValue(true));

        // Create alwaysFalseVoter stub
        $alwaysFalseVoter = $this->createMock(VoterInterface::class);
        $alwaysFalseVoter->expects($this->any())
            ->method('pass')
            ->will($this->returnValue(false));

        // Create a registry with the voter stubs in it
        $registry = new VoterRegistry();
        $registry->addVoter($alwaysTrueVoter, 'AlwaysTrueVoter');
        $registry->addVoter($alwaysFalseVoter, 'AlwaysFalseVoter');

        return $registry;
    }
}
