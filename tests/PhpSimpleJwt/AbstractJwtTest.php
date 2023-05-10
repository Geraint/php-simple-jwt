<?php

declare(strict_types=1);

namespace PhpSimpleJwt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers PhpSimpleJwt\AbstractJwt
 */
class AbstractJwtTest extends TestCase
{
    private static array $validHeader = [
        'alg' => 'RS256',
        'typ' => 'JWT',
    ];

    private static array $validPayload = [
    ];

    /**
     * @test
     */
    public function headerMustHaveAlg(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $header = self::$validHeader;
        unset($header['alg']);
        $this->getMockForAbstractClass(AbstractJwt::class, [ $header, self::$validPayload ]);
    }

    /**
     * @test
     */
    public function headerMustHaveTyp(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $header = self::$validHeader;
        unset($header['typ']);
        $this->getMockForAbstractClass(AbstractJwt::class, [ $header, self::$validPayload ]);
    }

    /**
     * @test
     */
    public function headerMustHaveTypOfJwt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $header = self::$validHeader;
        $header['typ'] = 'FOOBAR';
        $this->getMockForAbstractClass(AbstractJwt::class, [ $header, self::$validPayload ]);
    }
}
