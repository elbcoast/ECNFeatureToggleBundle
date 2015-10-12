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

use Ecn\FeatureToggleBundle\Voters\AlwaysTrueVoter;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class AlwaysTrueVoterTest extends \PHPUnit_Framework_TestCase
{
    public function testVoterPass()
    {
        $voter = new AlwaysTrueVoter();

        $this->assertTrue($voter->pass());
    }

    public function testVoterSetParams()
    {
        $voter = new AlwaysTrueVoter();

        $params = new ParameterBag(array());

        $this->assertNull($voter->setParams($params));
    }
}
