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

use Ecn\FeatureToggleBundle\Exception\VoterNotFoundException;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class VoterRegistry
{
    /**
     * @var array<string, VoterInterface>
     */
    private array $voters = [];

    /**
     * @param VoterInterface $voter The voter service to add
     * @param string         $alias The alias of the added voter
     */
    public function addVoter(VoterInterface $voter, string $alias): void
    {
        $this->voters[$alias] = $voter;
    }

    /**
     * Returns a voter by its alias
     *
     * @param string $alias
     *
     * @return VoterInterface
     *
     * @throws VoterNotFoundException
     */
    public function getVoter(string $alias): VoterInterface
    {
        if (isset($this->voters[$alias])) {
            return $this->voters[$alias];
        }

        throw new VoterNotFoundException(sprintf('No voter with this alias: "%s" is registered', $alias));
    }
}
