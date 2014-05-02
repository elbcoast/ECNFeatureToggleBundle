<?php

namespace Ecn\FeatureToggleBundle\Voters;

/**
 * Interface VoterInterface
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
Interface VoterInterface
{
  /**
   * Add additional parameters from the feature definition
   *
   * @param array $params
   *
   * @return mixed
   */
  public function setParams(array $params);

  /**
   * Check if conditions for a feature are matched
   *
   * @return bool
   */
  public function pass();

}