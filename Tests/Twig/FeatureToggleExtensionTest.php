<?php

namespace Ecn\FeatureToggleBundle\Tests\Twig;

use Ecn\FeatureToggleBundle\Service\FeatureService;
use Ecn\FeatureToggleBundle\Twig\FeatureToggleExtension;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;

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

}