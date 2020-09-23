<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\ResetPassword;

use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getPasswordResetToken());

        $user->resetPassword($token->getValue(), $now, $hash = 'hash');

        self::assertNull($user->getPasswordResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testResetNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        self::assertNull($user->getPasswordResetToken());

        $this->expectExceptionMessage('resetting_not_requested');

        $user->resetPassword($token->getValue(), $now, $hash = 'hash');
    }

    public function testTokenNotValid(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getPasswordResetToken());

        $this->expectExceptionMessage('invalid_confirmation_token');

        $user->resetPassword('wrong_token', $now, $hash = 'hash');
    }

    public function testTokenExpired(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getPasswordResetToken());

        $this->expectExceptionMessage('invalid_confirmation_token');

        $user->resetPassword('wrong_token', $now->modify('+ 2 hour'), $hash = 'hash');
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(Uuid::uuid4()->toString(), $date);
    }
}
