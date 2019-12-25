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

namespace Ecn\FeatureToggleBundle\Service;

use Ecn\FeatureToggleBundle\Exception\VoterNotFoundException;
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
     * FeatureService constructor
     *
     * @param array         $features
     * @param array         $defaultVoter
     * @param VoterRegistry $voterRegistry
     */
    public function __construct(array $features, array $defaultVoter, VoterRegistry $voterRegistry)
    {
        $this->features = $features;
        $this->voterRegistry = $voterRegistry;
        $this->defaultVoter = $defaultVoter;
    }

    /**
     * Check if a feature is enabled
     *
     * @param string $feature
     *
     * @return bool
     *
     * @throws VoterNotFoundException
     */
    public function has(string $feature): bool
    {
        if (!array_key_exists($feature, $this->features)) {
            return false;
        }

        try {
            $voter = $this->initVoter($feature);

            return $voter->pass();
        } catch (VoterNotFoundException $exception) {
            throw $exception;
        }
    }

    /**
     * Initializes a voter for a specific feature
     *
     * @param string $feature
     *
     * @return VoterInterface
     */
    protected function initVoter(string $feature): VoterInterface
    {
        $featureDefinition = $this->features[$feature];

        $voterName = $featureDefinition['voter'] ?: $this->defaultVoter['voter'];
        $voter = $this->voterRegistry->getVoter($voterName);


        $defaultParams = $this->defaultVoter['params'];
        $params = $featureDefinition['params'] ?: $defaultParams;

        $voter->setFeature($feature);
        $voter->setParams($params);

        return $voter;
    }
}
