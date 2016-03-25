<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Service;

use Ecn\FeatureToggleBundle\Voters\VoterInterface;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
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
     * @param               $features
     * @param VoterRegistry $voterRegistry
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
     * @return VoterInterface|null
     */
    protected function initVoter($feature)
    {
        $featureDefinition = $this->features[$feature];

        $voter = $this->voterRegistry->getVoter($featureDefinition['voter']);
        $params = array_key_exists('params', $featureDefinition) ? $featureDefinition['params'] : array();

        $voter->setFeature($feature);
        $voter->setParams($params);

        return $voter;

    }
}
