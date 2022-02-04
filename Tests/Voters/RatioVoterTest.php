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
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class RatioVoterTest extends TestCase
{
    public array $stickyValues = [];

    public function testLowRatioVoterPass(): void
    {
        $voter = $this->getRatioVoter(0.1);
        $hits = $this->executeTestIteration($voter);

        $this->assertLessThan(100 - $hits, $hits);
    }

    public function testStickyRatioVoterPassOnNullSession(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $voter = $this->getRatioVoter(0.5, true, false);
        $voter->pass();
    }

    /**
     * @dataProvider dataProvider
     *
     * @param bool $hasSession
     */
    public function testHighRatioVoterPass(bool $hasSession): void
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
    public function testZeroRatioVoterPass(bool $hasSession): void
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
    public function testOneRatioVoterPass(bool $hasSession): void
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
    public function dataProvider(): array
    {
        return [
            [true],
            [false],
        ];
    }

    public function testStickyRatioVoterPass(): void
    {
        $voter = $this->getRatioVoter(0.5, true);
        $initialPass = $voter->pass();
        $this->stickyValues = ['_ecn_featuretoggle_ratioTest' => $initialPass];

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
     * @param string $key
     *
     * @return bool
     */
    public function hasStickyCallback(string $key): bool
    {
        return isset($this->stickyValues[$key]);
    }

    /**
     * Callback for session stub
     *
     * @param string $key
     *
     * @return bool|null
     */
    public function getStickyCallback(string $key): bool|null
    {
        return $this->stickyValues[$key] ?? null;
    }

    protected function getRatioVoter(float $ratio, bool $sticky = false, bool $hasSession = true): RatioVoter
    {
        $session = null;

        if($hasSession)
        {
            // Create service stub
            /** @var Session&MockObject $session */
            $session = $this->createMock(SessionInterface::class);

            $session->method('get')->willReturnCallback([$this, 'getStickyCallback']);
            $session->method('has')->willReturnCallback([$this, 'hasStickyCallback']);
        }

        $params = ['ratio' => $ratio, 'sticky' => $sticky];

        $voter = new RatioVoter($session);
        $voter->setFeature('ratioTest');
        $voter->setParams($params);

        return $voter;
    }

    /**
     * Executes the Tests n time returning the number of passes
     *
     * @param RatioVoter $ratioVoter
     *
     * @return int
     */
    private function executeTestIteration(RatioVoter $ratioVoter): int
    {
        $hits = 0;
        $iterationCount = 100;

        for ($i = 1; $i <= $iterationCount; $i++) {
            if ($ratioVoter->pass()) {
                $hits++;
            }
        }

        return $hits;
    }
}
