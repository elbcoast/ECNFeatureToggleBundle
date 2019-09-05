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

use Ecn\FeatureToggleBundle\Voters\VoterInterface;
use Ecn\FeatureToggleBundle\Voters\VoterTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class VoterTraitTest extends TestCase
{
    public function testVoterSetParams(): void
    {
        $voter = new VoterStub();

        $params = [];

        $this->assertNull($voter->setParams($params));
    }

    public function testVoterSetFeature(): void
    {
        $voter = new VoterStub();

        $this->assertNull($voter->setFeature('feature'));
    }
}

class VoterStub implements VoterInterface
{
    use VoterTrait;

    public function pass(): bool
    {
        return true;
    }
}
