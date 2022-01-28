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

namespace Ecn\FeatureToggleBundle\EventListener;

use Closure;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Persistence\Proxy;
use Ecn\FeatureToggleBundle\Configuration\Feature;
use Ecn\FeatureToggleBundle\Service\FeatureService;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ControllerListener implements EventSubscriberInterface
{
    /**
     * @var Reader
     */
    protected Reader $reader;

    /**
     * @var FeatureService
     */
    protected FeatureService $featureService;

    /**
     * @param Reader         $reader
     * @param FeatureService $featureService
     */
    public function __construct(Reader $reader, FeatureService $featureService)
    {
        $this->reader = $reader;
        $this->featureService = $featureService;
    }

    /**
     * @param ControllerEvent $event
     *
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        // We can't resolve the controller name from non-array callables.
        $controller = $event->getController();

        if ((!$controller instanceof Closure)
            && is_object($controller)
            && method_exists($controller, '__invoke')
        ) {
            $controller = [$controller, '__invoke'];
        }

        if (!is_array($controller)) {
            return;
        }

        /** @var class-string $className */
        $className = $this->getRealClass(is_object($controller[0]) ? get_class($controller[0]) : $controller[0]);

        $object = new ReflectionClass($className);
        $method = $object->getMethod($controller[1]);

        $controllerAnnotations = $this->reader->getClassAnnotations($object);
        $actionAnnotations = $this->reader->getMethodAnnotations($method);

        $this->checkFeature($controllerAnnotations);
        $this->checkFeature($actionAnnotations);
    }

    /**
     * {@inheritDoc}
     */
    #[ArrayShape([KernelEvents::CONTROLLER => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * Checks for features in annotations.
     *
     * @param array $annotations
     *
     * @throws NotFoundHttpException If a feature is found, but not enabled.
     */
    private function checkFeature(array $annotations): void
    {
        foreach ($annotations as $feature) {
            if (($feature instanceof Feature) && !$this->featureService->has($feature->name)) {
                throw new NotFoundHttpException();
            }
        }
    }

    /**
     * Gets the real class name of a class name that could be a proxy.
     *
     * @param string $class
     *
     * @return string
     */
    private function getRealClass(string $class): string
    {
        if (false === $pos = strrpos($class, '\\'.Proxy::MARKER.'\\')) {
            return $class;
        }

        return substr($class, $pos + Proxy::MARKER_LENGTH + 2);
    }
}
