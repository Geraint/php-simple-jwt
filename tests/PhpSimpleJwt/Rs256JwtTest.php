<?php

declare(strict_types=1);

namespace PhpSimpleJwt;

use PHPUnit\Framework\TestCase;

use InvalidArgumentException;
use JsonException;
use stdClass;

/**
 * @covers \PhpSimpleJwt\Rs256Jwt
 */
class Rs256JwtTest extends TestCase
{
    private static array $validHeader;

    private static array $validPayload;

    private string $privateKey = '';

    public static function setUpBeforeClass(): void
    {
        self::$validHeader =  ["alg" => "RS256","typ" => "JWT"];
        self::$validPayload =  [];
    }

    public function setUp(): void
    {
        $this->privateKey = file_get_contents(__DIR__ . '/../private_key.pem');
    }

    /**
     * @test
     */
    public function privateKeyMustBeValid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Rs256Jwt(self::$validHeader, self::$validPayload, 'not a valid private key');
    }

    /**
     * @test
     */
    public function headerMustHaveAlgOfRs256(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $header = self::$validHeader;
        $header['alg'] = 'FOOBAR';
        new Rs256Jwt($header, self::$validPayload, $this->privateKey);
    }

    /**
     * @test
     */
    public function canGetSignedToken(): void
    {
        $sut = new Rs256Jwt(self::$validHeader, self::$validPayload, $this->privateKey);
        $expected = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.e30.Ya3h_vlCH-rAjle2MsikDNCQKc2BIrN30hJFTtnlPveFCwO_sxZDQVeGD6XlFMoPngLyRtRuiywagEPQOSJsMjHcd3AkpdtuljlTG0Yatm3wuK3IJu28rwQW-JVdQ2y0_p6R1xQ6Jw5zkdIcE6EPfF2Qsj8Ex1tFsB1kOA-D8kIvTwZT6KarmmHr429mtXjJtRvL3l_iAtxt59zCWrNArhdFDq_zhG-67d7Ry5muiNUa7H04Cg4YOXFQ6QJTnDaEZK586Yg2fw9yi1MTpIKpKb9oHYSpn0SNK_5jUC4uiHcwHxO3C53b5d5mVLTe9Pfmf71sklvRCRF_h0kNM8FLww';
        $actual = $sut->getSignedToken();
        $this->assertSame($expected, $actual);
    }
}
