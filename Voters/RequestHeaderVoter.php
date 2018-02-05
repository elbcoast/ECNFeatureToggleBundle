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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author    Andras Debreczeni <dev@debreczeniandras.hu>
 */
final class RequestHeaderVoter implements VoterInterface
{
    use VoterTrait;
    
    /** @var array */
    private $headers = [];
    
    /** @var Request|null */
    private $request;
    
    /**
     * {@inheritdoc}
     */
    public function setParams(array $params)
    {
        $this->headers = array_key_exists('headers', $params) ? $params['headers'] : null;
    }
    
    /**
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }
    
    /**
     * This voter passes if ALL request headers provided match to their values.
     */
    public function pass()
    {
        if (!$this->headers) {
            return false;
        }
        
        if (!$this->request) {
            return false;
        }
        
        foreach ($this->headers as $headerKey => $headerValue) {
            if (!$this->request->headers->has($headerKey)) {
                return false;
            }
            
            if ($this->request->headers->get($headerKey) != $headerValue) {
                return false;
            }
        }
        
        return true;
    }
}
