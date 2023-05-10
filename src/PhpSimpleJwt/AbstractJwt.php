<?php

declare(strict_types=1);

namespace PhpSimpleJwt;

use InvalidArgumentException;
use stdClass;

abstract class AbstractJwt
{
    protected stdClass $header;

    protected stdClass $payload;

    public function __construct(array $header, array $payload)
    {
        $this->header = (object) $header;
        $this->payload = (object) $payload;
        $this->validate();
    }

    protected function validate(): void
    {
        $this->validateHeader();
    }

    protected function validateHeader(): void
    {
        if (! property_exists($this->header, 'alg')) {
            throw new InvalidArgumentException("header must have an alg");
        }
        if (! property_exists($this->header, 'typ')) {
            throw new InvalidArgumentException("header must have an typ");
        }
        if ($this->header->typ !== 'JWT') {
            throw new InvalidArgumentException("header typ must be 'JWT'");
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

    protected function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    abstract protected function getSignature(string $data): string;
}
