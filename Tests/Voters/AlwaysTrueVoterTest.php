<?php

namespace Ecn\FeatureToggleBundle\Tests\Voters;

use Ecn\FeatureToggleBundle\Voters\AlwaysTrueVoter;
use Symfony\Component\HttpFoundation\ParameterBag;


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

        $params = new ParameterBag(array());

        $this->assertNull($voter->setParams($params));
    }


}