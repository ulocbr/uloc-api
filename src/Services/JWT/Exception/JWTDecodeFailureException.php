<?php

namespace Uloc\ApiBundle\Services\JWT\Exception;

/**
 * JWTDecodeFailureException is thrown if an error occurs in the token decoding process.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class JWTDecodeFailureException extends JWTFailureException
{
    const INVALID_TOKEN    = 'invalid_token';

    const UNVERIFIED_TOKEN = 'unverified_token';

    const EXPIRED_TOKEN    = 'expired_token';
}
