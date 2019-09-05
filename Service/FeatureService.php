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
     * @var array
     */
    protected $defaultVoter;

    /**
     * @var VoterRegistry
     */
    protected $voterRegistry;


    /**
     * @param               $features
     * @param $defaultVoter
     * @param VoterRegistry $voterRegistry
     */
    public function __construct($features, $defaultVoter, VoterRegistry $voterRegistry)
    {
        $this->features = $features;
        $this->voterRegistry = $voterRegistry;
        $this->defaultVoter = $defaultVoter;
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

        $voterName = $featureDefinition['voter'] ? $featureDefinition['voter'] : $this->defaultVoter['voter'];
        $voter = $this->voterRegistry->getVoter($voterName);

        $defaultParams = $this->defaultVoter['params'];
        $params = $featureDefinition['params'] ? $featureDefinition['params'] : $defaultParams;

        $voter->setFeature($feature);
        $voter->setParams($params);

        return $voter;
    }
}
