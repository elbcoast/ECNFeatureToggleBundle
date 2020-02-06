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
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class RatioVoterTest extends \PHPUnit_Framework_TestCase
{
    public $stickyValues = array();

    /**
     * @dataProvider dataProvider
     *
     * @param bool $hasSession
     */
    public function testLowRatioVoterPass($hasSession)
    {
        $voter = $this->getRatioVoter(0.1, false, $hasSession);
        $hits = $this->executeTestIteration($voter);

        $this->assertLessThan(100 - $hits, $hits);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param bool $hasSession
     */
    public function testHighRatioVoterPass($hasSession)
    {
        $voter = $this->getRatioVoter(0.9, false, $hasSession);
        $hits = $this->executeTestIteration($voter);

        $this->assertGreaterThan(100 - $hits, $hits);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param bool $hasSession
     */
    public function testZeroRatioVoterPass($hasSession)
    {
        $voter = $this->getRatioVoter(0, false, $hasSession);
        $hits = $this->executeTestIteration($voter);

        $this->assertEquals(0, $hits);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param bool $hasSession
     */
    public function testOneRatioVoterPass($hasSession)
    {
        $voter = $this->getRatioVoter(1, false, $hasSession);
        $hits = $this->executeTestIteration($voter);

        $this->assertEquals(100, $hits);
    }

    /**
     * Simple data provider for $hasSession
     *
     * @return array
     */
    public function dataProvider()
    {
        return [
            [true],
            [false],
        ];
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
     * @expectedException InvalidArgumentException
     */
    public function testStickyRatioVoterPassOnNullSession()
    {
        $voter = $this->getRatioVoter(0.5, true, false);
        $voter->pass();
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

    protected function getRatioVoter($ratio, $sticky = false, $hasSession = true)
    {
        $session = null;

        if ($hasSession) {
             // Create service stub
             $session = $this->getMockBuilder(SessionInterface::class)
                ->disableOriginalConstructor()
                ->getMock();

             $session->method('get')->will($this->returnCallback(array($this, 'getStickyCallback')));
             $session->method('has')->will($this->returnCallback(array($this, 'hasStickyCallback')));
        }

        $params = array('ratio' => $ratio, 'sticky' => $sticky);

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
