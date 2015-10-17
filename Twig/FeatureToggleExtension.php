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

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class FeatureToggleExtension extends \Twig_Extension
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
     * @return array
     */
    public function getFunctions()
    {
        // Check if a feature is activated
        $checkFeatureFunction = new \Twig_SimpleFunction('feature', function ($value) {
            return $this->featureService->has($value);
        });

        return array($checkFeatureFunction);
    }

    /**
     * Returns the name of the extension
     *
     * @return string
     */
    public function getName()
    {
        return 'featuretoggle_extension';
    }
}
