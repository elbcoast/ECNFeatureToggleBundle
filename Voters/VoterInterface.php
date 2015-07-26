<?php

namespace Ecn\FeatureToggleBundle\Voters;

use Symfony\Component\HttpFoundation\ParameterBag;


/**
 * Interface VoterInterface
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
Interface VoterInterface
{
    /**
     * Add additional parameters from the feature definition
     *
     * @param ParameterBag $params
     *
     * @return void
     */
    public function setParams(ParameterBag $params);

    /**
     * Sets the name of the feature
     *
     * @param string $feature
     *
     * @return void
     */
    public function setFeature($feature);

    /**
     * Check if conditions for a feature are matched
     *
     * @return bool
     */
    public function pass();

}