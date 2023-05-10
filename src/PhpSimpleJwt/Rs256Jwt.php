<?php

declare(strict_types=1);

namespace PhpSimpleJwt;

use InvalidArgumentException;
use OpenSSLAsymmetricKey;
use UnexpectedValueException;
use stdClass;

class Rs256Jwt extends AbstractJwt
{
    public function __construct(array $header, array $payload, private string $privateKey)
    {
        parent::__construct($header, $payload);
    }

    protected function validate(): void
    {
        parent::validate();
        $this->validatePrivateKey();
    }

    protected function validateHeader(): void
    {
        parent::validateHeader();
        if ($this->header->alg !== 'RS256') {
            throw new InvalidArgumentException("header alg must be 'RS256'");
        }
    }

    private function validatePrivateKey(): void
    {
        $privateKey = openssl_pkey_get_private($this->privateKey);
        if (!$privateKey instanceof OpenSSLAsymmetricKey) {
            throw new InvalidArgumentException('private_key is not valid');
        }
    }

    public function getSignedToken(): string
    {
        $headerJson = json_encode($this->header, JSON_THROW_ON_ERROR);
        $headerEncoded = $this->base64UrlEncode($headerJson);
        $payloadJson = json_encode($this->payload, JSON_THROW_ON_ERROR);
        $payloadEncoded = $this->base64UrlEncode($payloadJson);
        $data = "{$headerEncoded}.{$payloadEncoded}";
        return "{$data}.{$this->getSignature($data)}";
    }

    protected function getSignature(string $data): string
    {
        $privateKey = openssl_pkey_get_private($this->privateKey);
        $result = openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if ($result === false) {
            $errorMessage = openssl_error_string();
            throw new UnexpectedValueException("Error signing token: {$errorMessage}");
        }
        return $this->base64UrlEncode($signature);
    }
}
