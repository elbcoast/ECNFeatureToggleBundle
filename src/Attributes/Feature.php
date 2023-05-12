<?php declare(strict_types=1);

/*
 * This file is part of the ECNFeatureToggle package.
 *
 * (c) Pierre Groth <pierre@elbcoast.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecn\FeatureToggleBundle\Attributes;

use Symfony\Contracts\Service\Attribute\Required;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Feature
{
    /**
     * Feature name to be checked.
     *
     * @param string $name
     */
    #[Required]
    public function __construct(public string $name = '')
    {
    }
}
