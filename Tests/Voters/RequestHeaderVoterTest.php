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

namespace Ecn\FeatureToggleBundle\Tests\Voters;

use Ecn\FeatureToggleBundle\Voters\RequestHeaderVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Andras Debreczeni <dev@debreczeniandras.hu>
 */
class RequestHeaderVoterTest extends TestCase
{
    public function testIsArrayAssociative(): void
    {
        $this->assertTrue(RequestHeaderVoter::isAssociativeArray(['X-cdn' => 1, ['X-Location' => 'CN']]));
    }

    public function testIsArrayNotAssociative(): void
    {
        $this->assertFalse(RequestHeaderVoter::isAssociativeArray(['X-cdn', 'X-Location']));
    }

    public function testNoCurrentRequestInRequestStack(): void
    {
        $voter = $this->getRequestHeaderVoter(new RequestStack(), []);

        $this->assertFalse($voter->pass());
    }

    private function getRequestHeaderVoter(RequestStack $requestStack, array $requestHeaders = []): RequestHeaderVoter
    {
        $voter = new RequestHeaderVoter();
        $voter->setRequest($requestStack);
        $voter->setParams(['headers' => $requestHeaders]);

        return $voter;
    }

    public function testNoRequestHeadersProvided(): void
    {
        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack(), []);

        $this->assertFalse($voter->pass());
    }

    private function getFakeRequestStack(array $headers = []): RequestStack
    {
        $fakeRequest = Request::create('/');
        $fakeRequest->headers->add($headers);

        $requestStack = new RequestStack();
        $requestStack->push($fakeRequest);

        return $requestStack;
    }

    public function testEqualOneRequestHeaderWithValue(): void
    {
        $requestHeaders = ['x-location' => 'CN'];
        $headerConfig = $requestHeaders;

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertTrue($voter->pass());
    }

    public function testOneRequestHeaderExists(): void
    {
        $requestHeaders = ['x-location' => '1'];
        $headerConfig = ['x-location'];

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertTrue($voter->pass());
    }

    public function testOneRequestHeaderCaseInsensitive(): void
    {
        $requestHeaders = ['X-Location' => '1'];
        $headerConfig = ['x-location'];

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertTrue($voter->pass());
    }

    public function testTwoRequestHeadersBothExists(): void
    {
        $requestHeaders = ['x-location' => 'CN', 'x-cdn' => 'Akamai'];
        $headerConfig = ['x-location', 'x-cdn'];

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertTrue($voter->pass());
    }

    public function testTwoRequestHeadersOneDoesNotExist(): void
    {
        $requestHeaders = ['x-location' => 'CN'];
        $headerConfig = ['x-location', 'x-cdn'];

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertFalse($voter->pass());
    }

    public function testNotEqualOneRequestHeaderWithValue(): void
    {
        $requestHeaders = ['x-location' => 'CN'];
        $headerConfig = ['x-location' => 'cn'];

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertFalse($voter->pass());
    }

    public function testEqualRequestNameCaseInsensitivityWithValue(): void
    {
        $requestHeaders = ['x-location' => 'CN'];
        $headerConfig = ['X-Location' => 'CN'];

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertTrue($voter->pass());
    }

    public function testEqualTwoRequestsHeaderWithValue(): void
    {
        $requestHeaders = ['x-location' => 'CN', 'x-cdn' => 'Akamai'];
        $headerConfig = $requestHeaders;

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertTrue($voter->pass());
    }

    public function testAtLeastOneNonEqualTwoRequestsHeaderWithValue(): void
    {
        $requestHeaders = ['x-location' => 'CN', 'x-cdn' => 'Akamai'];
        $headerConfig = ['x-location' => 'cn', 'x-cdn' => 'Akamai'];

        $voter = $this->getRequestHeaderVoter($this->getFakeRequestStack($requestHeaders), $headerConfig);

        $this->assertFalse($voter->pass());
    }
}
