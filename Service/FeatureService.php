<?php

namespace Ecn\FeatureToggleBundle\Service;

/**
 * Class FeatureService
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
class FeatureService
{
  /**
   * Contains the defined features
   *
   * @var array
   */
  protected $features;


  /**
   * Constructor.
   *
   * @param $features
   */
  public function __construct($features)
  {
    $this->features = $features;
  }


  /**
   * Check if a feature is enabled
   *
   * @param $value
   *
   * @return bool
   */
  public function has($value)
  {
    return array_key_exists($value, $this->features);
  }

}