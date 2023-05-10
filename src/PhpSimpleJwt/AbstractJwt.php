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

    abstract function getSignedToken(): string;
}
