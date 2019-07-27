<?php

namespace Uloc\ApiBundle\Services\JWT\JWSProvider;

/**
 * Interface for classes that are able to create and load JSON web signatures (JWS).
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface JWSProviderInterface
{
    /**
     * Creates a new JWS signature from a given payload.
     *
     * @param array $payload
     * @param array $header
     *
     * @return \Uloc\ApiBundle\Services\JWT\Signature\CreatedJWS
     */
    public function create(array $payload, array $header = []);

    /**
     * Loads an existing JWS signature from a given JWT token.
     *
     * @param string $token
     *
     * @return \Uloc\ApiBundle\Services\JWT\Signature\LoadedJWS
     */
    public function load($token);
}
