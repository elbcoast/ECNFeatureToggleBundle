<?php

namespace Ecn\FeatureToggleBundle\Tests\Voters;

use Ecn\FeatureToggleBundle\Voters\VoterRegistry;


class VoterRegistryTest extends \PHPUnit_Framework_TestCase
{

    public function testAddVotersToRegistry()
    {
        // Mock a voter
        $voterOne = $this->getMock('\Ecn\FeatureToggleBundle\Voters\VoterInterface');
        $voterTwo = $this->getMock('\Ecn\FeatureToggleBundle\Voters\VoterInterface');

        // Fill up registry
        $registry = new VoterRegistry();
        $registry->addVoter($voterOne, 'myFirstTestVoter');
        $registry->addVoter($voterTwo, 'mySecondTestVoter');

        // Test for voters
        $this->assertSame($voterOne, $registry->getVoter('myFirstTestVoter'));
        $this->assertSame($voterTwo, $registry->getVoter('mySecondTestVoter'));

    }


    /**
     * @expectedException \Ecn\FeatureToggleBundle\Exception\VoterNotFoundException
     */
    public function testUnknownVoterException()
    {
        // Mock a voter
        $voterOne = $this->getMock('\Ecn\FeatureToggleBundle\Voters\VoterInterface');

        // Fill up registry
        $registry = new VoterRegistry();
        $registry->addVoter($voterOne, 'myFirstTestVoter');

        $unknownVoter = $registry->getVoter('unknownVoter');
    }

}