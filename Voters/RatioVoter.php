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
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
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
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(ParameterBag $params)
    {
        $this->ratio = $params->get('ratio', 0.5);

    }

    /**
     * {@inheritdoc}
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    /**
     * {@inheritdoc}
     */
    public function pass()
    {
        $value = $this->ratio * 100;

        return rand(0, 99) < $value ? true : false;
    }
}
