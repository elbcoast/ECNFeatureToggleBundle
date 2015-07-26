<?php

namespace Ecn\FeatureToggleBundle\Voters;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Session\Session;


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
     * @var string
     */
    protected $feature;

    /**
     * @var Session
     */
    protected $session;


    /**
     * Constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

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
     * @param string $feature
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;
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