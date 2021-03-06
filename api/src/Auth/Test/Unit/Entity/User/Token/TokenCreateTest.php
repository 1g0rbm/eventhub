<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Auth\Entity\User\Token
 */
class TokenCreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $value   = Uuid::uuid4()->toString();
        $expires = new DateTimeImmutable();

        $token = new Token($value, $expires);

        self::assertEquals($value, $token->getValue());
        self::assertEquals($expires, $token->getExpires());
    }

    public function testCase(): void
    {
        $value   = Uuid::uuid4()->toString();
        $expires = new DateTimeImmutable();

        $token = new Token(mb_strtoupper($value), $expires);

        self::assertEquals($value, $token->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Token('1234567890', new DateTimeImmutable());
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Token('', new DateTimeImmutable());
    }
}
