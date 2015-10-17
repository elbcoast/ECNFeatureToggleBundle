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

use Ecn\FeatureToggleBundle\Voters\VoterInterface;
use Ecn\FeatureToggleBundle\Voters\VoterTrait;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class VoterTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testVoterSetParams()
    {
        $voter = new VoterStub();

        $params = new ParameterBag(array());

        $this->assertNull($voter->setParams($params));
    }

    public function testVoterSetFeature()
    {
        $voter = new VoterStub();

        $this->assertNull($voter->setFeature('feature'));
    }
}

class VoterStub implements VoterInterface
{
    use VoterTrait;

    public function pass()
    {
        return true;
    }
}
