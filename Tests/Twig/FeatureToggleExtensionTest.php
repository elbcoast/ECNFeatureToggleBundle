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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @author Pierre Groth <pierre@elbcoast.net>
 */
class FeatureToggleExtensionTest extends TestCase
{
    public function testCallable(): void
    {
        // Define response map for service stub
        $map = [
            ['testfeature', true],
            ['unknownfeature', false]
        ];

        /** @var MockObject&FeatureService $service */
        $service = $this->getMockBuilder(FeatureService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service
            ->method('has')
            ->willReturnMap($map);

        // Create extension
        $extension = new FeatureToggleExtension($service);

        $functions = $extension->getFunctions();

        // Check if functions are returned as array
        $this->assertIsArray($functions);

        // Check if the function is a twig function
        $this->assertInstanceOf(TwigFunction::class, $functions[0]);

        $callable = $functions[0]->getCallable();

        // Check if callable returns true for a known feature
        $this->assertTrue($callable('testfeature'));

        // Check if callable returns false for an unknown feature
        $this->assertFalse($callable('unknownfeature'));

    }
}
