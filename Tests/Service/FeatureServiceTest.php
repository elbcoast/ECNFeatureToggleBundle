<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Tests\Configuration;

use Ecn\FeatureToggleBundle\Service\FeatureService;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class FeatureServiceTest extends \PHPUnit_Framework_TestCase
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
        $alwaysTrueVoter = $this->getMock('\Ecn\FeatureToggleBundle\Voters\VoterInterface');
        $alwaysTrueVoter->expects($this->any())
            ->method('pass')
            ->will($this->returnValue(true));

        // Create alwaysFalseVoter stub
        $alwaysFalseVoter = $this->getMock('\Ecn\FeatureToggleBundle\Voters\VoterInterface');
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
