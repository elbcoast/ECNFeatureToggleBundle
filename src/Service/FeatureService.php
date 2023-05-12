<?php declare(strict_types=1);

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
     * Contains the defined features.
     */
    protected array $features;

    protected array $defaultVoter;
    protected VoterRegistry $voterRegistry;

    /**
     * FeatureService constructor.
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
     * Check if a feature is enabled.
     *
     * @param string $feature
     *
     * @return bool
     */
    public function has(string $feature): bool
    {
        if (!isset($this->features[$feature])) {
            return false;
        }

        return $this->initVoter($feature)->pass();
    }

    /**
     * Initializes a voter for a specific feature.
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
