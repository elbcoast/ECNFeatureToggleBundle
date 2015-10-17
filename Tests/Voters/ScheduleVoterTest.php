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
use Ecn\FeatureToggleBundle\Voters\ScheduleVoter;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ScheduleVoterTest extends \PHPUnit_Framework_TestCase
{
    public function testNoScheduleIsSet()
    {
        $voter = $this->getScheduleVoter(null);

        $this->assertTrue($voter->pass());
    }

    public function testInvalidScheduleIsSet()
    {
        $voter = $this->getScheduleVoter('THIS IS INVALID');

        $this->assertFalse($voter->pass());
    }

    public function testEarlierScheduleIsSet()
    {
        $voter = $this->getScheduleVoter((new \DateTime())->modify('-1 second')->format(\DateTime::RSS));

        $this->assertFalse($voter->pass());
    }

    public function testLaterScheduleIsSet()
    {
        $voter = $this->getScheduleVoter((new \DateTime())->modify('+1 second')->format(\DateTime::RSS));

        $this->assertTrue($voter->pass());
    }

    protected function getScheduleVoter($schedule)
    {
        $voter = new ScheduleVoter();
        $voter->setParams(new ParameterBag(array('schedule' => $schedule)));

        return $voter;
    }
}
