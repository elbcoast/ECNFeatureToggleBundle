<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Twig;

use Ecn\FeatureToggleBundle\Service\FeatureService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class FeatureToggleExtension extends AbstractExtension
{
    /**
     * @var FeatureService
     */
    protected $featureService;

    /**
     * @param FeatureService $featureService
     */
    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    /**
     * @return FeatureService
     */
    public function getFeatureService()
    {
        return $this->featureService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('feature', [$this, 'hasFeature']),
        ];
    }

    public function getTokenParsers()
    {
        return [
            new FeatureToggleTokenParser(),
        ];
    }

    public function hasFeature($feature): bool
    {
        return $this->featureService->has($feature);
    }
}
