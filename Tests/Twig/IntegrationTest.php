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

namespace Ecn\FeatureToggleBundle\Tests\Twig;

use Ecn\FeatureToggleBundle\Service\FeatureService;
use Ecn\FeatureToggleBundle\Twig\FeatureToggleExtension;
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
     *
     * @param string $file
     * @param string $message
     * @param string $condition
     * @param array $templates
     * @param bool $exception
     * @param array $outputs
     * @param string $deprecation
     */
    public function testIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation = ''): void
    {
        parent::testIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation);
    }

    /**
     * @dataProvider getLegacyTests
     * @group legacy
     *
     * @param string $file
     * @param string $message
     * @param string $condition
     * @param array $templates
     * @param bool $exception
     * @param array $outputs
     * @param string $deprecation
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
