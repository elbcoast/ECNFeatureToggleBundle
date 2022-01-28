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
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
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
    private ControllerListener $listener;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var ControllerEvent
     */
    private ControllerEvent $event;

    /**
     * Set up Tests
     */
    public function setUp(): void
    {
        $this->listener = new ControllerListener(
            new AnnotationReader(),
            new FeatureService([], [], new VoterRegistry())
        );
        $this->request = $this->createRequest();

        // trigger to autoload the @Feature annotation
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
        $this->listener = new ControllerListener(new AnnotationReader(), new FeatureService([], [], new VoterRegistry()));
        $this->request = new Request([], [], []);
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

        $this->event = $this->getControllerEvent([$controller, 'barAction'], $this->request);

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

        $this->event = $this->getControllerEvent([$controller, 'barAction'], $this->request);

        $this->listener->onKernelController($this->event);
    }

    /**
     * @param object|array|string $controller
     * @param Request               $request
     *
     * @return ControllerEvent
     */
    protected function getControllerEvent(object|array|string $controller, Request $request): ControllerEvent
    {
        /** @var Kernel $mockKernel */
        $mockKernel = $this->getMockForAbstractClass(HttpKernelInterface::class);

        return new ControllerEvent($mockKernel, $controller, $request, HttpKernelInterface::MAIN_REQUEST);
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
        $this->event = $this->getControllerEvent([$controller, 'barAction'], $this->request);

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
        $this->event = $this->getControllerEvent([$controller, 'barAction'], $this->request);

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

        $this->event = $this->getControllerEvent($controller, $this->request);

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

        $this->event = $this->getControllerEvent([$controller, 'barAction'], $this->request);

        $this->listener->onKernelController($this->event);
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
