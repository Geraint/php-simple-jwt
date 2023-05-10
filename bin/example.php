<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpSimpleJwt\Rs256Jwt;

$privateKey = file_get_contents(__DIR__ . '/../tests/private_key.pem');
assert(is_string($privateKey));

$jwt = new Rs256Jwt(
    [
        'alg' => 'RS256',
        'typ' => 'JWT',
    ],
    [
        'name' => 'Joe Bloggs',
    ],
    $privateKey
);

$signedToken = $jwt->getSignedToken();
echo "{$signedToken}\n";
