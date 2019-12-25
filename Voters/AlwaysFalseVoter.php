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

namespace Ecn\FeatureToggleBundle\Voters;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class AlwaysFalseVoter implements VoterInterface
{
    use VoterTrait;

    /**
     * {@inheritdoc}
     */
    public function pass(): bool
    {
        return false;
    }
}
