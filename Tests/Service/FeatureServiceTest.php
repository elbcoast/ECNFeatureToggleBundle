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

        // Create voter stub
        $voter = $this->getMock('\Ecn\FeatureToggleBundle\Voters\VoterInterface');
        $voter->expects($this->any())
            ->method('pass')
            ->will($this->returnValue(true));

        // Create a registry with the voter stub in it
        $registry = new VoterRegistry();
        $registry->addVoter($voter, 'AlwaysTrueVoter');

        // Create service
        $service = new FeatureService($features, $registry);

        $this->assertTrue($service->has('testfeature'));
        $this->assertFalse($service->has('unknownfeature'));
    }
}
