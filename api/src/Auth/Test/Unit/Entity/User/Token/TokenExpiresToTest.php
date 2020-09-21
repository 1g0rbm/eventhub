<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TokenExpiresToTest extends TestCase
{
    public function testNotExpired(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expired = new DateTimeImmutable()
        );

        self::assertFalse($token->isExpiresTo($expired->modify('-1 sec')));
        self::assertTrue($token->isExpiresTo($expired->modify('+1 sec')));
        self::assertTrue($token->isExpiresTo($expired));
    }
}
