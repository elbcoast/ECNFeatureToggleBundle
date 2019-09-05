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

use DateTime;
use Ecn\FeatureToggleBundle\Voters\ScheduleVoter;
use PHPUnit\Framework\TestCase;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ScheduleVoterTest extends TestCase
{
    public function testNoScheduleIsSet(): void
    {
        $voter = $this->getScheduleVoter(null);

        $this->assertTrue($voter->pass());
    }

    protected function getScheduleVoter($schedule): ScheduleVoter
    {
        $voter = new ScheduleVoter();
        $voter->setParams(['schedule' => $schedule]);

        return $voter;
    }

    public function testInvalidScheduleIsSet(): void
    {
        $voter = $this->getScheduleVoter('THIS IS INVALID');

        $this->assertFalse($voter->pass());
    }

    public function testEarlierScheduleIsSet(): void
    {
        $voter = $this->getScheduleVoter((new DateTime())->modify('-1 second')->format(DateTime::RSS));

        $this->assertTrue($voter->pass());
    }

    public function testLaterScheduleIsSet(): void
    {
        $voter = $this->getScheduleVoter((new DateTime())->modify('+1 second')->format(DateTime::RSS));

        $this->assertFalse($voter->pass());
    }
}
