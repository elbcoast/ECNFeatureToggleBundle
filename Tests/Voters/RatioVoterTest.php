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
    public $stickyValues = array();

    public function testLowRatioVoterPass()
    {
        $voter = $this->getRatioVoter(0.1);
        $hits = $this->executeTestIteration($voter);

        $this->assertLessThan(100 - $hits, $hits);
    }

    public function testHighRatioVoterPass()
    {
        $voter = $this->getRatioVoter(0.9);
        $hits = $this->executeTestIteration($voter);

        $this->assertGreaterThan(100 - $hits, $hits);
    }

    public function testZeroRatioVoterPass()
    {
        $voter = $this->getRatioVoter(0);
        $hits = $this->executeTestIteration($voter);

        $this->assertEquals(0, $hits);
    }

    public function testOneRatioVoterPass()
    {
        $voter = $this->getRatioVoter(1);
        $hits = $this->executeTestIteration($voter);

        $this->assertEquals(100, $hits);
    }

    public function testStickyRatioVoterPass()
    {
        $voter = $this->getRatioVoter(0.5, true);
        $initialPass = $voter->pass();
        $this->stickyValues = array('_ecn_featuretoggle_ratiotest' => $initialPass);

        if ($initialPass) {
            $requiredHits = $this->executeTestIteration($voter) == 100;
        } else {
            $requiredHits = $this->executeTestIteration($voter) == 0;
        }

        $this->assertTrue($requiredHits);
    }

    /**
     * Executes the tests n time returning the number of passes
     *
     * @param RatioVoter $ratioVoter
     * @param int        $iterationCount
     *
     * @return int
     */
    private function executeTestIteration(RatioVoter $ratioVoter, $iterationCount = 100)
    {
        $hits = 0;

        for ($i = 1; $i <= $iterationCount; $i++) {
            if ($ratioVoter->pass()) {
                $hits++;
            }
        }

        return $hits;
    }

    protected function getRatioVoter($ratio, $sticky = false)
    {
        // Create service stub
        $session = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()
            ->getMock();

        $session->method('get')->will($this->returnCallback(array($this, 'getStickyCallback')));
        $session->method('has')->will($this->returnCallback(array($this, 'hasStickyCallback')));

        $params = new ParameterBag(array('ratio' => $ratio, 'sticky' => $sticky));

        $voter = new RatioVoter($session);
        $voter->setFeature('ratiotest');
        $voter->setParams($params);

        return $voter;
    }

    /**
     * Callback for session stub
     *
     * @param $key
     *
     * @return bool
     */
    public function hasStickyCallback($key)
    {
        return array_key_exists($key, $this->stickyValues);
    }

    /**
     * Callback for session stub
     *
     * @param $key
     *
     * @return null
     */
    public function getStickyCallback($key)
    {
        return array_key_exists($key, $this->stickyValues) ? $this->stickyValues[$key] : null;
    }
}
