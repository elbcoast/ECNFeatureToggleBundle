<?php

namespace Ecn\FeatureToggleBundle\Tests\Voters;

use Ecn\FeatureToggleBundle\Voters\RatioVoter;
use Symfony\Component\HttpFoundation\ParameterBag;

class RatioVoterTest extends \PHPUnit_Framework_TestCase
{


    public function testVoterSetParams()
    {
        $voter = new RatioVoter();

        $params = new ParameterBag(array('ratio' => 0.5));

        $this->assertNull($voter->setParams($params));
    }


    public function testLowRatioVoterPass()
    {
        $voter = new RatioVoter();
        $params = new ParameterBag(array('ratio' => 0.1));
        $voter->setParams($params);

        $misses = 0;
        $hits = 0;

        for ($i = 1; $i <= 100; $i++) {
            if ($voter->pass()) {
                $hits++;
            } else {
                $misses++;
            }
        }

        $this->assertTrue($misses > $hits);
    }


    public function testHighRatioVoterPass()
    {
        $voter = new RatioVoter();
        $params = new ParameterBag(array('ratio' => 0.9));
        $voter->setParams($params);

        $misses = 0;
        $hits = 0;

        for ($i = 1; $i <= 100; $i++) {
            if ($voter->pass()) {
                $hits++;
            } else {
                $misses++;
            }
        }

        $this->assertTrue($misses < $hits);
    }


    public function testZeroRatioVoterPass()
    {
        $voter = new RatioVoter();
        $params = new ParameterBag(array('ratio' => 0));
        $voter->setParams($params);

        $hits = 0;

        for ($i = 1; $i <= 100; $i++) {
            if ($voter->pass()) {
                $hits++;
            }
        }

        $this->assertTrue($hits == 0);
    }


    public function testOneRatioVoterPass()
    {
        $voter = new RatioVoter();
        $params = new ParameterBag(array('ratio' => 1));
        $voter->setParams($params);

        $misses = 0;

        for ($i = 1; $i <= 100; $i++) {
            if (!$voter->pass()) {
                $misses++;
            }
        }

        $this->assertTrue($misses == 0);
    }


}