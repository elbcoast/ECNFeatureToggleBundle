<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Voters;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class ScheduleVoter implements VoterInterface
{
    use VoterTrait;

    /**
     * A valid DateTime representation
     *
     * @var string
     */
    private $schedule;

    /**
     * {@inheritdoc}
     */
    public function setParams(ParameterBag $params)
    {
        $this->schedule = $params->get('schedule');
    }

    /**
     * {@inheritdoc}
     */
    public function pass()
    {
        // Don't pass if schedule is invalid
        try {
            $schedule = new \DateTime($this->schedule);
        } catch (\Exception $e) {
            return false;
        }

        return new \DateTime() >= $schedule;
    }
}
