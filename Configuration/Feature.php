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

namespace Ecn\FeatureToggleBundle\Configuration;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @Annotation()
 *
 * @Target({"CLASS", "METHOD"})
 */
class Feature
{
    /**
     * Feature name to be checked.
     *
     * @var string
     *
     * @Required()
     */
    public $name;
}
