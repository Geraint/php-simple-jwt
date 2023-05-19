# PHP Simple JWT

![Build Status](https://github.com/Geraint/php-simple-jwt/actions/workflows/build-and-test.yml/badge.svg)

**Warning**

I built this as a learning excercise.
It is not recommended for Production use.
Use at your own risk.

---

A very simple implementation of the RS256 algorithm for signing JWT's.

It would be trivial to add support for more algorithms.

## Installation

```sh
composer require geraint/php-simple-jwt:dev-main
```

## Usage

The example script in the `/bin` directory looks like this:

```php
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
```

Running `php bin/example.php` outputs:

```
eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSm9lIEJsb2dncyJ9.PHb2DMbz95SiFjZWe5lre82pZXuBQ3P-PiHEZX8vJCOlxicvWhf9f8rm8_PCNisTvIcox0I6VyLVc1bwH6bfLvmC_n2Wkx4JI4KMybjQfgNNpjaRObt8SR6AxLvSZjeScbXwflMxP82UeexaJ5THxAT0y77Fvwb53T7W4haxRLwsU17OG4BcSi8_vLofXcluRJUO0Iz9N0Q6UKe_bw5aJFG9ZSvYNy1CQzUAOucSrL7YIu9Dt6zdwlTUcvLkriZc1jr2ItMjlSE2rbKcKx7HMpbtHEZ8GUlWTUX_wj7q6MYI1YQP3h_VW7kLUEaiG5TF8FamaU4DTu6pRbat5mtkhg
```
