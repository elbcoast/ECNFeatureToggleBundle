<?php
declare(strict_types=1);

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
     * @var SessionInterface|null
     */
    protected ?SessionInterface $session;

    /**
     * @var float
     */
    protected float $ratio = 0.5;

    /**
     * @var bool
     */
    protected bool $sticky = false;

    /**
     * @param SessionInterface|null $session
     */
    public function __construct(?SessionInterface $session = null)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $params): void
    {
        $this->ratio = $params['ratio'] ?? 0.5;
        $this->sticky = $params['sticky'] ?? false;
    }

    /**
     * {@inheritdoc}
     */
    public function pass(): bool
    {
        if ($this->sticky) {
            $pass = $this->getStickyRatioPass();
        } else {
            $pass = $this->getRatioPass();
        }

        return $pass;
    }

    /**
     * Get a persisted pass value
     *
     * @return bool
     */
    protected function getStickyRatioPass(): bool
    {
        if (null === $this->session) {
            throw new InvalidArgumentException(sprintf('The service "%s" has a dependency on the session', get_class($this)));
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

    /**
     * Check if the ratio passes
     *
     * @return bool
     */
    protected function getRatioPass(): bool
    {
        $ratio = $this->ratio * 100;

        return rand(0, 99) < $ratio;
    }
}
