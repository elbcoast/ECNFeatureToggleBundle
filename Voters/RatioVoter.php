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
    use VoterTrait;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var float
     */
    protected $ratio = 0.5;

    /**
     * @var bool
     */
    protected $sticky = false;

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
        $this->sticky = $params->get('sticky', false);
    }

    /**
     * {@inheritdoc}
     */
    public function pass()
    {
        if($this->sticky) {
            $pass = $this->getStickyRatioPass();
        } else {
            $pass = $this->getRatioPass();
        }

        return $pass;
    }

    /**
     * Check if the ratio passes
     *
     * @return bool
     */
    protected function getRatioPass()
    {
        $ratio = $this->ratio * 100;

        return rand(0, 99) < $ratio;
    }

    /**
     * Get a persisted pass value
     *
     * @return bool
     */
    protected function getStickyRatioPass()
    {
        $sessionKey = '_ecn_featuretoggle_'.$this->feature;
        if ($this->session->has($sessionKey)) {
            $pass = $this->session->get($sessionKey);
        } else {
            $pass = $this->getRatioPass();
            $this->session->set($sessionKey, $pass);
        }

        return $pass;
    }
}
