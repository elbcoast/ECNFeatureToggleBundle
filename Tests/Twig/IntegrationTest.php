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

namespace Ecn\FeatureToggleBundle\Twig;

use Ecn\FeatureToggleBundle\Service\FeatureService;
use Ecn\FeatureToggleBundle\Voters\AlwaysTrueVoter;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;
use Twig\Test\IntegrationTestCase;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class IntegrationTest extends IntegrationTestCase
{
    public function getFixturesDir(): string
    {
        return dirname(__FILE__) . '/Fixtures/';
    }

    /**
     * @dataProvider getTests
     */
    public function testIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation = ''): void
    {
        parent::testIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation);
    }

    /**
     * @dataProvider getLegacyTests
     * @group legacy
     */
    public function testLegacyIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation = ''): void
    {
        parent::testLegacyIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation);
    }

    public function getExtensions(): array
    {
        $voterRegistry = new VoterRegistry();

        $voterRegistry->addVoter(new AlwaysTrueVoter(), 'AlwaysTrueVoter');

        $featureService = new FeatureService(
            [
                'feature' => [
                    'voter' => 'AlwaysTrueVoter',
                    'params' => [],
                ],
            ],
            [
                'voter' => 'AlwaysTrueVoter',
                'params' => [],
            ],
            $voterRegistry
        );

        return [
            new FeatureToggleExtension($featureService),
        ];
    }
}
