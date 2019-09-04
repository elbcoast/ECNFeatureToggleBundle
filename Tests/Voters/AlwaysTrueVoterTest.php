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
use PHPUnit\Framework\TestCase;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class AlwaysTrueVoterTest extends TestCase
{
    public function testVoterPass()
    {
        $voter = new AlwaysTrueVoter();

        $this->assertTrue($voter->pass());
    }
}
