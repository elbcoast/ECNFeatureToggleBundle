<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Tests\Twig;

use Ecn\FeatureToggleBundle\Service\FeatureService;
use Ecn\FeatureToggleBundle\Twig\FeatureToggleExtension;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class FeatureToggleExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testCallable()
    {
        // Define response map for service stub
        $map = [
            ['testfeature', true],
            ['unknownfeature', false]
        ];

        // Create service stub
        $service = $this->getMockBuilder('\Ecn\FeatureToggleBundle\Service\FeatureService')
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->any())
            ->method('has')
            ->will($this->returnValueMap($map));

        // Create extension
        $extension = new FeatureToggleExtension($service);

        $functions = $extension->getFunctions();

        // Check if functions are returned as array
        $this->assertInternalType('array', $functions);

        // Check if the function is a twig function
        $this->assertInstanceOf('Twig_SimpleFunction', $functions[0]);

        $callable = $functions[0]->getCallable();

        // Check if callable returns true for a known feature
        $this->assertTrue($callable('testfeature'));

        // Check if callable returns false for an unknown feature
        $this->assertFalse($callable('unknownfeature'));

    }

    public function testIfExtensionHasProperName()
    {
        // Create service stub
        $service = $this->getMockBuilder('\Ecn\FeatureToggleBundle\Service\FeatureService')
            ->disableOriginalConstructor()
            ->getMock();

        // Create extension
        $extension = new FeatureToggleExtension($service);

        $this->assertEquals('featuretoggle_extension', $extension->getName());

    }
}
