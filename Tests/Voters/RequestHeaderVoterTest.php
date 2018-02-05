<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Tests\Voters;

use Ecn\FeatureToggleBundle\Voters\RequestHeaderVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @group request-header
 * @author Andras Debreczeni <dev@debreczeniandras.hu>
 */
class RequestHeaderVoterTest extends \PHPUnit_Framework_TestCase
{
    
    public function testNoCurrentRequestInRequestStack()
    {
        $voter = $this->getRequestHeaderVoterWithoutCurrentRequest(new RequestStack(), null);
        
        $this->assertFalse($voter->pass());
    }
    
    public function testNoRequestHeadersProvided()
    {
        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack(), null);
        
        $this->assertFalse($voter->pass());
    }
    
    public function testEqualOneRequestHeader()
    {
        $requestHeaders = ['x-location' => 'CN'];
        $headerConfig = $requestHeaders;
        
        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);
        
        $this->assertTrue($voter->pass());
    }
    
    public function testNotEqualOneRequestHeader()
    {
        $requestHeaders = ['x-location' => 'CN'];
        $headerConfig = ['x-location' => 'cn'];
    
        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);
        
        $this->assertFalse($voter->pass());
    }
    
    public function testEqualRequestNameCaseInsensitivity()
    {
        $requestHeaders = ['x-location' => 'CN'];
        $headerConfig = ['X-Location' => 'CN'];
    
        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);
        
        $this->assertTrue($voter->pass());
    }
    
    public function testEqualTwoRequestsHeader()
    {
        $requestHeaders = ['x-location' => 'CN', 'x-cdn' => 'Akamai'];
        $headerConfig = $requestHeaders;
        
        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);
        
        $this->assertTrue($voter->pass());
    }
    
    public function testAtLeastOneNonEqualTwoRequestsHeader()
    {
        $requestHeaders = ['x-location' => 'CN', 'x-cdn' => 'Akamai'];
        $headerConfig = ['x-location' => 'cn', 'x-cdn' => 'Akamai'];
        
        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);
        
        $this->assertFalse($voter->pass());
    }
    
    private function getRequestHeaderVoter(RequestStack $requestStack, $requestHeaders = null)
    {
        $voter = new RequestHeaderVoter();
        $voter->setRequest($requestStack);
        $voter->setParams(['headers' => $requestHeaders]);
        
        return $voter;
    }
    
    private function getFakeRequestStack($headers = [])
    {
        // you can overwrite any value you want through the constructor if you need more control
        $fakeRequest = Request::create('/', 'GET');
        $fakeRequest->headers->add($headers);
    
        $requestStack = new RequestStack();
        $requestStack->push($fakeRequest);
        
        return $requestStack;
    }
    
    private function getRequestHeaderVoterWithoutCurrentRequest(RequestStack $requestStack, $requestHeaders = [])
    {
        $voter = new RequestHeaderVoter();
        
        $voter->setRequest($requestStack);
        $voter->setParams(['headers' => $requestHeaders]);
        
        return $voter;
    }
}
