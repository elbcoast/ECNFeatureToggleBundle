<?php

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Ecn\FeatureToggleBundle\Configuration\Feature;
use Ecn\FeatureToggleBundle\Service\FeatureService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
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
    protected $reader;

    /**
     * @var FeatureService
     */
    protected $featureService;

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
     * Throws an exception when the given feature is not enabled.
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        // We can't resolve the controller name from non-array callables.
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
        $object = new \ReflectionClass($className);
        $method = $object->getMethod($controller[1]);

        $controllerAnnotations = $this->reader->getClassAnnotations($object);
        $actionAnnotations = $this->reader->getMethodAnnotations($method);

        $this->checkFeature($controllerAnnotations);
        $this->checkFeature($actionAnnotations);
    }

    /**
     * Checks for features in annotations.
     *
     * @param array $annotations
     *
     * @throws NotFoundHttpException If a feature is found, but not enabled.
     */
    protected function checkFeature(array $annotations)
    {
        foreach ($annotations as $feature) {
            if ($feature instanceof Feature) {
                if (!$this->featureService->has($feature->name)) {
                    throw new NotFoundHttpException();
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
