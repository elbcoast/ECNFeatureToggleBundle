<?php declare(strict_types=1);

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Voters;

use DateTime;
use Exception;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class ScheduleVoter implements VoterInterface
{
    use VoterTrait;

    /**
     * A valid DateTime representation.
     */
    private string $schedule = '';

    /**
     * {@inheritdoc}
     */
    public function setParams(array $params): void
    {
        $this->schedule = $params['schedule'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function pass(): bool
    {
        // Don't pass if schedule is invalid
        if ('' === $this->schedule) {
            return true;
        }

        try {
            $schedule = new \DateTime($this->schedule);
        } catch (Exception) {
            return false;
        }

        return new \DateTime() >= $schedule;
    }
}
