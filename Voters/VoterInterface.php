<?php
declare (strict_types=1);

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
interface VoterInterface
{
    /**
     * Add additional parameters from the feature definition
     *
     * @param array $params
     */
    public function setParams(array $params): void;

    /**
     * Sets the name of the feature
     *
     * @param string $feature
     *
     * @return void
     */
    public function setFeature(string $feature): void;

    /**
     * Check if conditions for a feature are matched
     *
     * @return bool
     */
    public function pass(): bool;
}
