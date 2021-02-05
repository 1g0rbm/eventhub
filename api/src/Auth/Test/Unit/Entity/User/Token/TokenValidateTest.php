<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Auth\Entity\User\Token
 */
class TokenValidateTest extends TestCase
{
    public function testValid(): void
    {
        $value   = Uuid::uuid4()->toString();
        $expires = new DateTimeImmutable();

        $token = new Token($value, $expires);

        $token->validate($token->getValue(), $token->getExpires()->modify('-1 day'));

        self::assertEquals($value, $token->getValue());
        self::assertEquals($expires, $token->getExpires());
    }

    public function testExpired(): void
    {
        $value   = Uuid::uuid4()->toString();
        $expires = new DateTimeImmutable();

        $token = new Token($value, $expires);

        $this->expectExceptionMessage('expired_confirmation_token');

        $token->validate($token->getValue(), $token->getExpires()->modify('+1 day'));
    }

    public function testInvalid(): void
    {
        $value   = Uuid::uuid4()->toString();
        $expires = new DateTimeImmutable();

        $token = new Token($value, $expires);

        $this->expectExceptionMessage('invalid_confirmation_token');

        $token->validate('invalid_token', $token->getExpires()->modify('-1 day'));
    }
}
