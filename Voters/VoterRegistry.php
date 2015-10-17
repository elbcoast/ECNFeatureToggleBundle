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

use Ecn\FeatureToggleBundle\Exception\VoterNotFoundException;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class VoterRegistry
{
    /**
     * @var array
     */
    private $voters;

    public function __construct()
    {
        $this->voters = array();
    }

    /**
     * @param VoterInterface $voter The voter service to add
     * @param string         $alias The alias of the added voter
     */
    public function addVoter(VoterInterface $voter, $alias)
    {
        $this->voters[$alias] = $voter;
    }

    /**
     * Returns a voter by its alias
     *
     * @param string $alias
     *
     * @throws VoterNotFoundException
     *
     * @return VoterInterface|null
     */
    public function getVoter($alias)
    {
        if (array_key_exists($alias, $this->voters)) {
            return $this->voters[$alias];
        }

        throw new VoterNotFoundException();
    }
}
