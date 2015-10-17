<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Tests\Voters;

use Ecn\FeatureToggleBundle\Voters\RatioVoter;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class RatioVoterTest extends \PHPUnit_Framework_TestCase
{
    public function testLowRatioVoterPass()
    {
        $voter = $this->getRatioVoter();
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
        $voter = $this->getRatioVoter();
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
        $voter = $this->getRatioVoter();
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
        $voter = $this->getRatioVoter();
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

    protected function getRatioVoter()
    {
        // Create service stub
        $session = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()
            ->getMock();

        return new RatioVoter($session);
    }
}
