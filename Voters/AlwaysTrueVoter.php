<?php

namespace Ecn\FeatureToggleBundle\Voters;

/**
 * AlwaysTrueVoter
 *
 * PHP Version 5.4
 *
 * @author    Pierre Groth <pierre@elbcoast.net>
 * @copyright 2014
 * @license   MIT
 *
 */
class AlwaysTrueVoter implements VoterInterface
{

  public function setParams(array $params)
  {
  }

  /**
   * @return bool
   */
  public function pass()
  {
    return true;
  }

}