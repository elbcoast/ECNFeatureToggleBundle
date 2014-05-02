<?php

namespace Ecn\FeatureToggleBundle\Tests\Configuration;

use Ecn\FeatureToggleBundle\Service\FeatureService;

class FeatureServiceTest extends \PHPUnit_Framework_TestCase
{


  public function testIfFeatureMatches()
  {

    $features = [
      'testfeature' => [
        'voter' => 'AlwaysTrueVoter',
        'params' => []
      ]
    ];

    $service = new FeatureService($features);

    $this->assertTrue($service->has('testfeature'));
    $this->assertFalse($service->has('unknownfeature'));


  }

}