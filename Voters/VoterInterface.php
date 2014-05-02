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
  public function setParams(array $params);

  public function pass();

}