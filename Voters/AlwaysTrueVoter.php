<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Voters;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class AlwaysTrueVoter implements VoterInterface
{
    /**
     * {@inheritdoc}
     */
    public function setParams(ParameterBag $params)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setFeature($feature)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function pass()
    {
        return true;
    }
}
