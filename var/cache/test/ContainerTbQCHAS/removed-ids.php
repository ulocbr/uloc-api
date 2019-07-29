<?php

return [
    'Psr\\Container\\ContainerInterface' => true,
    'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
    'Uloc\\ApiBundle\\Api\\ResponseFactory' => true,
    'Uloc\\ApiBundle\\Manager\\PersonManager' => true,
    'Uloc\\ApiBundle\\Services\\JWT\\Encoder\\JWTEncoderInterface' => true,
    'Uloc\\ApiBundle\\Services\\JWT\\JWSProvider\\JWSProviderInterface' => true,
    'Uloc\\ApiBundle\\Services\\Log\\SystemLog' => true,
    'uloc_api.encoder.lcobucci' => true,
    'uloc_api.jws_provider.lcobucci' => true,
    'uloc_api.jwt_token_authenticator' => true,
    'uloc_api.key_loader.abstract' => true,
    'uloc_api.key_loader.raw' => true,
    'uloc_api.native.logger' => true,
    'uloc_api.object_manager' => true,
];
