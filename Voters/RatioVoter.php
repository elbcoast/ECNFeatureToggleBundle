<?php

namespace Ecn\FeatureToggleBundle\Voters;

use Symfony\Component\HttpFoundation\ParameterBag;


/**
 * RatioVoter
 *
 * PHP Version 5.5
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2015
 * @license   MIT
 *
 */
class RatioVoter implements VoterInterface
{


    /**
     * @var float
     */
    protected $ratio;

    /**
     * {@inheritdoc}
     *
     * @param array $params
     * @return void
     */
    public function setParams(ParameterBag $params)
    {
        $this->ratio = $params->get('ratio', 0.5);

    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function pass()
    {
        $value = $this->ratio * 100;

        return rand(0, 99) < $value ? true : false;
    }

}