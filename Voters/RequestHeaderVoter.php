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

namespace Ecn\FeatureToggleBundle\Voters;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author    Andras Debreczeni <dev@debreczeniandras.hu>
 */
final class RequestHeaderVoter implements VoterInterface
{
    use VoterTrait;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var Request|null
     */
    private $request;

    /**
     * @var bool
     */
    private $checkHeaderValues;

    /**
     * {@inheritdoc}
     */
    public function setParams(array $params): void
    {
        $headers = array_key_exists('headers', $params) ? $params['headers'] : null;

        $this->checkHeaderValues = $headers ? static::isAssociativeArray($headers) : false;
        $this->headers           = $headers;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack): void
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * {@inheritDoc}
     */
    public function pass(): bool
    {
        if (!$this->headers) {
            return false;
        }

        if (!$this->request) {
            return false;
        }

        foreach ($this->headers as $key => $value) {
            $headerKey = $this->checkHeaderValues ? $key : $value;
            if (!$this->request->headers->has($headerKey)) {
                return false;
            }

            if ($this->checkHeaderValues) {
                if ($this->request->headers->get($key) != $value) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks if the provided header configuration is associative or not.
     * If yes, then we check both keys and values, otherwise only the existence of the request header.
     *
     * @param array $arr
     *
     * @return bool
     */
    public static function isAssociativeArray(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
