<?php

namespace Ecn\FeatureToggleBundle\Voters;

use Ecn\FeatureToggleBundle\Voters\VoterInterface;
use Ecn\FeatureToggleBundle\Exception\VoterNotFoundException;

/**
 * Class VoterRegistry
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
Class VoterRegistry
{
    /**
     * @var array
     */
    private $voters;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->voters = array();
    }


    /**
     * @param VoterInterface $voter The voter service to add
     * @param string $alias The alias of the added voter
     */
    public function addVoter(VoterInterface $voter, $alias)
    {
        $this->voters[$alias] = $voter;
    }


    /**
     * Returns a voter by its alias
     *
     * @param $alias
     *
     * @throws \Ecn\FeatureToggleBundle\Exception\VoterNotFoundException
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