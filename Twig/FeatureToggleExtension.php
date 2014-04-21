<?php

namespace Ecn\FeatureToggleBundle\Twig;

use Ecn\FeatureToggleBundle\Service\FeatureService;

/**
 * Class FeatureToggleExtension
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
class FeatureToggleExtension extends \Twig_Extension
{

  /**
   * The FeatureService
   *
   * @var \Ecn\FeatureToggleBundle\Service\FeatureService
   */
  protected $featureService;


  /**
   * Constructor.
   *
   * @param FeatureService $featureService
   */
  public function __construct(FeatureService $featureService)
  {

    $this->featureService = $featureService;

  }


  /**
   * @return array
   */
  public function getFunctions()
  {
    // Check if a feature is activated
    $checkFeatureFunction = new \Twig_SimpleFunction('feature', function($value) {
      return $this->featureService->has($value);
    });

    return array($checkFeatureFunction);
  }


  /**
   * Returns the name of the extension
   *
   * @return string
   */
  public function getName()
  {
    return 'featuretoggle_extension';
  }
}