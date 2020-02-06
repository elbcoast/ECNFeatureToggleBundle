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

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class RatioVoter implements VoterInterface
{
    use VoterTrait;

    /**
     * @var SessionInterface
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
     * @param SessionInterface|null $session
     */
    public function __construct(SessionInterface $session = null)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $params)
    {
        $this->ratio = array_key_exists('ratio', $params) ? $params['ratio'] : 0.5;
        $this->sticky = array_key_exists('sticky', $params) ? $params['sticky'] : false;
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
        if (null === $this->session) {
            throw new InvalidArgumentException('The service '.get_class($this).' has a dependency on the session');
        }

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
