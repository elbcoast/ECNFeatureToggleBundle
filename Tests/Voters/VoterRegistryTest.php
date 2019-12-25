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

use Ecn\FeatureToggleBundle\Exception\VoterNotFoundException;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;
use PHPUnit\Framework\TestCase;
use Ecn\FeatureToggleBundle\Voters\VoterInterface;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class VoterRegistryTest extends TestCase
{
    public function testAddVotersToRegistry(): void
    {
        // Mock a voter
        $voterOne = $this->createMock(VoterInterface::class);
        $voterTwo = $this->createMock(VoterInterface::class);

        // Fill up registry
        $registry = new VoterRegistry();
        $registry->addVoter($voterOne, 'myFirstTestVoter');
        $registry->addVoter($voterTwo, 'mySecondTestVoter');

        // Test for voters
        $this->assertSame($voterOne, $registry->getVoter('myFirstTestVoter'));
        $this->assertSame($voterTwo, $registry->getVoter('mySecondTestVoter'));

    }

    public function testUnknownVoterException(): void
    {
        $this->expectException(VoterNotFoundException::class);
        // Mock a voter
        $voterOne = $this->createMock(VoterInterface::class);

        // Fill up registry
        $registry = new VoterRegistry();
        $registry->addVoter($voterOne, 'myFirstTestVoter');

        $unknownVoter = $registry->getVoter('unknownVoter');
    }
}
