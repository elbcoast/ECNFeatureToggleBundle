<?php

namespace Ecn\FeatureToggleBundle\Voters;

use Symfony\Component\HttpFoundation\ParameterBag;


/**
 * AlwaysTrueVoter
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
class AlwaysTrueVoter implements VoterInterface
{

    /**
     * {@inheritdoc}
     *
     * @param ParameterBag $params
     */
    public function setParams(ParameterBag $params)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @param string $feature
     */
    public function setFeature($feature)
    {
    }

    /**
     * @return bool
     */
    public function pass()
    {
        return true;
    }

}