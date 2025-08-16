<?php

namespace Uloc\ApiBundle\Services\JWT\JWSProvider;

// Import classes from lcobucci/jwt v5 and supporting packages.
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\Clock\SystemClock;
use Uloc\ApiBundle\Services\JWT\KeyLoader\RawKeyLoader;
use Uloc\ApiBundle\Services\JWT\Signature\CreatedJWS;
use Uloc\ApiBundle\Services\JWT\Signature\LoadedJWS;

/**
 * @final
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class LcobucciJWSProvider implements JWSProviderInterface
{
    /**
     * @var RawKeyLoader
     */
    private $keyLoader;

    /**
     * @var Signer
     */
    private $signer;

    /**
     * Holds the JWT configuration object (signer, keys, parser, validator).
     *
     * @var Configuration
     */
    private $configuration;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var int
     */
    private $clockSkew;

    /**
     * @param RawKeyLoader $keyLoader
     * @param string       $cryptoEngine
     * @param string       $signatureAlgorithm
     * @param int|null     $ttl
     * @param int          $clockSkew
     *
     * @throws \InvalidArgumentException If the given crypto engine is not supported
     */
    public function __construct(RawKeyLoader $keyLoader, $cryptoEngine, $signatureAlgorithm, $ttl, $clockSkew)
    {
        if ('openssl' !== $cryptoEngine) {
            throw new \InvalidArgumentException(sprintf('The %s provider supports only "openssl" as crypto engine.', __CLASS__));
        }

        if (null !== $ttl && !is_numeric($ttl)) {
            throw new \InvalidArgumentException(sprintf('The TTL should be a numeric value, got %s instead.', $ttl));
        }

        if (null !== $clockSkew && !is_numeric($clockSkew)) {
            throw new \InvalidArgumentException(sprintf('The clock skew should be a numeric value, got %s instead.', $clockSkew));
        }

        $this->keyLoader = $keyLoader;
        $this->signer    = $this->getSignerForAlgorithm($signatureAlgorithm);
        $this->ttl       = $ttl;
        $this->clockSkew = $clockSkew;

        // Initialise the configuration using the new API. When using HMAC
        // algorithms, we rely on a symmetric key; for other algorithms (RSA/ECDSA),
        // we use asymmetric keys.
        $privateKey = $this->keyLoader->loadKey(RawKeyLoader::TYPE_PRIVATE);
        $publicKey  = $this->keyLoader->loadKey(RawKeyLoader::TYPE_PUBLIC);
        $passphrase = $this->keyLoader->getPassphrase();

        if ($this->signer instanceof Hmac) {
            $this->configuration = Configuration::forSymmetricSigner(
                $this->signer,
                InMemory::plainText($privateKey)
            );
        } else {
            $this->configuration = Configuration::forAsymmetricSigner(
                $this->signer,
                InMemory::plainText($privateKey, $passphrase ?? ''),
                InMemory::plainText($publicKey)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $payload, array $header = [])
    {
        $now = new DateTimeImmutable();

        // Start building a new token using the configuration
        $builder = $this->configuration->builder()
            ->issuedAt($now);

        // Apply TTL to set expiration, if defined
        if (null !== $this->ttl) {
            $builder = $builder->expiresAt($now->modify('+' . $this->ttl . ' seconds'));
        }

        // Set custom headers
        foreach ($header as $k => $v) {
            $builder = $builder->withHeader($k, $v);
        }

        // Set payload claims
        foreach ($payload as $name => $value) {
            $builder = $builder->withClaim($name, $value);
        }

        try {
            // Build and sign the token
            $token = $builder->getToken(
                $this->configuration->signer(),
                $this->configuration->signingKey()
            );
            return new CreatedJWS($token->toString(), true);
        } catch (\Throwable $e) {
            // If signing fails, return a failure flag with an empty token string
            return new CreatedJWS('', false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($token)
    {
        // Parse the string into a token instance
        $jwt = $this->configuration->parser()->parse((string) $token);
        if (!$jwt instanceof UnencryptedToken) {
            return new LoadedJWS([], false, null !== $this->ttl, [], $this->clockSkew);
        }

        // Extract all claims into an associative array. Convert date claims to UNIX timestamps,
        // as LoadedJWS expects numeric values for exp/iat claims.
        $payload = [];
        foreach ($jwt->claims()->all() as $name => $value) {
            if ($value instanceof \DateTimeInterface) {
                $payload[$name] = $value->getTimestamp();
            } else {
                $payload[$name] = $value;
            }
        }

        // Build validation constraints: signature must be valid
        $constraints = [
            new SignedWith($this->configuration->signer(), $this->configuration->verificationKey()),
        ];
        // If TTL is set, also validate time-based claims with a leeway
        if (null !== $this->ttl) {
            $constraints[] = new LooseValidAt(new SystemClock(new \DateTimeZone('UTC')), $this->clockSkew ?? 0);
        }

        $valid = $this->configuration->validator()->validate($jwt, ...$constraints);

        return new LoadedJWS(
            $payload,
            $valid,
            null !== $this->ttl,
            $jwt->headers()->all(),
            $this->clockSkew
        );
    }

    private function getSignerForAlgorithm($signatureAlgorithm)
    {
        $signerMap = [
            'HS256' => Signer\Hmac\Sha256::class,
            'HS384' => Signer\Hmac\Sha384::class,
            'HS512' => Signer\Hmac\Sha512::class,
            'RS256' => Signer\Rsa\Sha256::class,
            'RS384' => Signer\Rsa\Sha384::class,
            'RS512' => Signer\Rsa\Sha512::class,
            'EC256' => Signer\Ecdsa\Sha256::class,
            'EC384' => Signer\Ecdsa\Sha384::class,
            'EC512' => Signer\Ecdsa\Sha512::class,
        ];

        if (!isset($signerMap[$signatureAlgorithm])) {
            throw new \InvalidArgumentException(
                sprintf('The algorithm "%s" is not supported by %s', $signatureAlgorithm, __CLASS__)
            );
        }

        $signerClass = $signerMap[$signatureAlgorithm];

        return new $signerClass();
    }

    // The sign() and verify() helper methods were used by older versions of the
    // lcobucci/jwt library. With v5 these responsibilities are covered by
    // the Configuration instance and validation constraints. These methods are
    // retained here solely for reference and are no longer used.
}
