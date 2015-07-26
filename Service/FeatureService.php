<?php

namespace Ecn\FeatureToggleBundle\Service;

use Ecn\FeatureToggleBundle\Voters\VoterRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class FeatureService
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
class FeatureService
{
    /**
     * Contains the defined features
     *
     * @var array
     */
    protected $features;

    /**
     * @var VoterRegistry
     */
    protected $voterRegistry;


    /**
     * Constructor.
     *
     * @param                                               $features
     * @param \Ecn\FeatureToggleBundle\Voters\VoterRegistry $voterRegistry
     */
    public function __construct($features, VoterRegistry $voterRegistry)
    {
        $this->features = $features;
        $this->voterRegistry = $voterRegistry;
    }


    /**
     * Check if a feature is enabled
     *
     * @param $value
     *
     * @return bool
     */
    public function has($value)
    {
        if (!array_key_exists($value, $this->features)) {
            return false;
        }

        $feature = $this->features[$value];

        $voter = $this->voterRegistry->getVoter($feature['voter']);
        $params = new ParameterBag($feature['params']);

        $voter->setParams($params);

        return $voter->pass();
    }

}