<?php

namespace Ecn\FeatureToggleBundle\Tests\Voters;

use Ecn\FeatureToggleBundle\Voters\AlwaysTrueVoter;


class AlwaysTrueVoterTest extends \PHPUnit_Framework_TestCase
{

  public function testVoterPass()
  {
    $voter = new AlwaysTrueVoter();

    $this->assertTrue($voter->pass());
  }


  public function testVoterSetParams()
  {
    $voter = new AlwaysTrueVoter();

    $this->assertNull($voter->setParams(array()));
  }


}