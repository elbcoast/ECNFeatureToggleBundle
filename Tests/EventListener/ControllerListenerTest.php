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

namespace Ecn\FeatureToggleBundle\Tests\EventListener;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Ecn\FeatureToggleBundle\Configuration\Feature;
use Ecn\FeatureToggleBundle\EventListener\ControllerListener;
use Ecn\FeatureToggleBundle\Service\FeatureService;
use Ecn\FeatureToggleBundle\Tests\EventListener\Fixture\FooControllerFeatureAtClass;
use Ecn\FeatureToggleBundle\Tests\EventListener\Fixture\__CG__\Ecn\FeatureToggleBundle\Tests\EventListener\Fixture\FooControllerFeatureAtClass as ProxyFooControllerFeatureAtClass;
use Ecn\FeatureToggleBundle\Tests\EventListener\Fixture\FooControllerFeatureAtClassAndMethod;
use Ecn\FeatureToggleBundle\Tests\EventListener\Fixture\FooControllerFeatureAtInvoke;
use Ecn\FeatureToggleBundle\Tests\EventListener\Fixture\FooControllerFeatureAtMethod;
use Ecn\FeatureToggleBundle\Tests\EventListener\Fixture\FooControllerFeatureAtStaticMethod;
use Ecn\FeatureToggleBundle\Voters\VoterRegistry;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Chadha Amara <chadha.amara@db-n.com>
 */
class ControllerListenerTest extends TestCase
{
    /**
     * @var ControllerListener
     */
    private $listener;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FilterControllerEvent
     */
    private $event;

    /**
     * @var FeatureService&MockObject
     */
    private $mockListener;

    /**
     * Set up tests
     */
    public function setUp(): void
    {
        $this->listener = new ControllerListener(
            new AnnotationReader(),
            new FeatureService([], [], new VoterRegistry())
        );
        $this->request = $this->createRequest();

        // trigger the autoloading of the @Feature annotation
        class_exists(Feature::class);
    }

    /**
     * @return Request
     */
    protected function createRequest(): Request
    {
        return new Request([], [], []);
    }

    public function tearDown(): void
    {
        $this->listener = null;
        $this->request = null;
    }

    /**
     * Test Annotation Feature at method
     *
     * @throws ReflectionException
     */
    public function testFeatureAnnotationAtMethod(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $controller = new FooControllerFeatureAtMethod();

        $this->event = $this->getFilterControllerEvent([$controller, 'barAction'], $this->request);

        $this->listener->onKernelController($this->event);
    }

    /**
     * Test Annotation Feature at static method
     *
     * @throws ReflectionException
     */
    public function testFeatureAnnotationAtStaticMethod(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $controller = new FooControllerFeatureAtStaticMethod();

        $this->event = $this->getFilterControllerEvent([$controller, 'barAction'], $this->request);

        $this->listener->onKernelController($this->event);
    }

    /**
     * @param         $controller
     * @param Request $request
     *
     * @return FilterControllerEvent
     */
    protected function getFilterControllerEvent($controller, Request $request): FilterControllerEvent
    {
        /** @var Kernel|MockBuilder $mockKernel */
        $mockKernel = $this->getMockForAbstractClass(Kernel::class, ['', '']);

        return new FilterControllerEvent($mockKernel, $controller, $request, HttpKernelInterface::MASTER_REQUEST);
    }

    /**
     * Test Annotation Feature at class
     *
     * @throws ReflectionException
     */
    public function testFeatureAnnotationAtClass(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $controller = new FooControllerFeatureAtClass();
        $this->event = $this->getFilterControllerEvent([$controller, 'barAction'], $this->request);

        $this->listener->onKernelController($this->event);
    }

    /**
     * Test Annotation Feature at method and class
     *
     * @throws ReflectionException
     */
    public function testFeatureAnnotationAtClassAndMethod(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $controller = new FooControllerFeatureAtClassAndMethod();
        $this->event = $this->getFilterControllerEvent([$controller, 'barAction'], $this->request);

        $this->listener->onKernelController($this->event);
    }

    /**
     * Test Annotation Feature at __invoke method
     *
     * @throws ReflectionException
     */
    public function testFeatureAnnotationAtObjectInvoke(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $controller = new FooControllerFeatureAtInvoke();

        $this->event = $this->getFilterControllerEvent($controller, $this->request);

        $this->listener->onKernelController($this->event);
    }

    /**
     * Test Proxy extends class with Annotation Feature
     *
     * @throws ReflectionException
     */
    public function testFeatureProxyExtendsAnnotation(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $controller = new ProxyFooControllerFeatureAtClass();

        $this->event = $this->getFilterControllerEvent([$controller, 'barAction'], $this->request);

        $this->listener->onKernelController($this->event);
    }

    /**
     * @param callable $controller
     *
     * @throws ReflectionException
     * @throws AnnotationException
     *
     * @dataProvider callableDataProvider
     */
    public function testAvoidClosure(callable $controller): void
    {
        /** @var FeatureService&MockObject $featureService */
        $featureService = $this->createMock(FeatureService::class);
        $featureService->expects($this->never())->method('has');

        $listener = new ControllerListener(
            new AnnotationReader(),
            $featureService
        );

        $event = $this->getFilterControllerEvent($controller, $this->request);

        $listener->onKernelController($event);
    }

    /**
     * @return array
     */
    public function callableDataProvider(): array
    {
        return [
            [static function () {return 'test';}]
        ];
    }
}
