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
    public function pass()
    {
        $ratio = $this->ratio * 100;

        return rand(0, 99) < $ratio;
    }
}
