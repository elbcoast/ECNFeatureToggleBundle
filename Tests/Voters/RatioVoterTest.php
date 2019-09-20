<?php
declare(strict_types=1);

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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class RatioVoterTest extends TestCase
{
    public $stickyValues = [];

    public function testLowRatioVoterPass(): void
    {
        $voter = $this->getRatioVoter(0.1);
        $hits = $this->executeTestIteration($voter);

        $this->assertLessThan(100 - $hits, $hits);
    }

    protected function getRatioVoter($ratio, $sticky = false): RatioVoter
    {
        // Create service stub
        /** @var Session&MockObject $session */
        $session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $session->method('get')->willReturnCallback([$this, 'getStickyCallback']);
        $session->method('has')->willReturnCallback([$this, 'hasStickyCallback']);

        $params = ['ratio' => $ratio, 'sticky' => $sticky];

        $voter = new RatioVoter($session);
        $voter->setFeature('ratiotest');
        $voter->setParams($params);

        return $voter;
    }

    /**
     * Executes the tests n time returning the number of passes
     *
     * @param RatioVoter $ratioVoter
     * @param int $iterationCount
     *
     * @return int
     */
    private function executeTestIteration(RatioVoter $ratioVoter, $iterationCount = 100): int
    {
        $hits = 0;

        for ($i = 1; $i <= $iterationCount; $i++) {
            if ($ratioVoter->pass()) {
                $hits++;
            }
        }

        return $hits;
    }

    public function testHighRatioVoterPass(): void
    {
        $voter = $this->getRatioVoter(0.9);
        $hits = $this->executeTestIteration($voter);

        $this->assertGreaterThan(100 - $hits, $hits);
    }

    public function testZeroRatioVoterPass(): void
    {
        $voter = $this->getRatioVoter(0);
        $hits = $this->executeTestIteration($voter);

        $this->assertEquals(0, $hits);
    }

    public function testOneRatioVoterPass(): void
    {
        $voter = $this->getRatioVoter(1);
        $hits = $this->executeTestIteration($voter);

        $this->assertEquals(100, $hits);
    }

    public function testStickyRatioVoterPass(): void
    {
        $voter = $this->getRatioVoter(0.5, true);
        $initialPass = $voter->pass();
        $this->stickyValues = ['_ecn_featuretoggle_ratiotest' => $initialPass];

        if ($initialPass) {
            $requiredHits = $this->executeTestIteration($voter) === 100;
        } else {
            $requiredHits = $this->executeTestIteration($voter) === 0;
        }

        $this->assertTrue($requiredHits);
    }

    /**
     * Callback for session stub
     *
     * @param $key
     *
     * @return bool
     */
    public function hasStickyCallback($key): bool
    {
        return array_key_exists($key, $this->stickyValues);
    }

    /**
     * Callback for session stub
     *
     * @param $key
     *
     * @return mixed|null
     */
    public function getStickyCallback($key)
    {
        return $this->stickyValues[$key] ?? null;
    }
}
