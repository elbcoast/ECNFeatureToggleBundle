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
     * @param $feature
     *
     * @return bool
     */
    public function has($feature)
    {
        if (!array_key_exists($feature, $this->features)) {
            return false;
        }

        $voter = $this->initVoter($feature);

        return $voter->pass();
    }


    /**
     * Initializes a voter for a specific feature
     *
     * @param $feature
     *
     * @return \Ecn\FeatureToggleBundle\Voters\VoterInterface|null
     */
    protected function initVoter($feature)
    {
        $featureDefinition = $this->features[$feature];

        $voter = $this->voterRegistry->getVoter($featureDefinition['voter']);
        $params = new ParameterBag($featureDefinition['params']);

        $voter->setFeature($feature);
        $voter->setParams($params);

        return $voter;

    }

}
