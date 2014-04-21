<?php

namespace Ecn\FeatureToggleBundle\Tests\Configuration;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ecn\FeatureToggleBundle\DependencyInjection\EcnFeatureToggleExtension;

class EcnFeatureToggleBundleTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @var ContainerBuilder
   */
  private $configuration;


  /**
   * Test if the default configuration for a feature
   * contains the default settings for voter and params
   */
  public function testDefaultSettings()
  {
    $this->createConfiguration(array('features' => array('testfeature'=>array())));

    // Load feature config
    $features = $this->configuration->getParameter('features');

    $this->assertEquals('AlwaysTrueVoter', $features['testfeature']['voter']);
    $this->assertEquals(array(), $features['testfeature']['params']);

  }


  /**
   * @param array $config
   *
   * @return ContainerBuilder
   */
  private function createConfiguration($config = array())
  {
    $this->configuration = new ContainerBuilder();
    $loader = new EcnFeatureToggleExtension();
    $loader->load(array($config), $this->configuration);
    $this->assertTrue($this->configuration instanceof ContainerBuilder);
  }


  protected function tearDown()
  {
    unset($this->configuration);
  }
}