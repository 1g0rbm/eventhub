<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\JoinByEmail;

use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers User
 */
class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmationToken($token = $this->createToken())
            ->buildJoinByEmail();

        self::assertEquals($user->getJoinConfirmToken(), $token);
        self::assertFalse($user->isActive());

        $user->confirmJoin($token->getValue(), $token->getExpires()->modify('-1 day'));

        self::assertNull($user->getJoinConfirmToken());
        self::assertTrue($user->isActive());
    }

    public function testExpired(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmationToken($token = $this->createToken())
            ->buildJoinByEmail();

        $this->expectExceptionMessage('expired_confirmation_token');

        $user->confirmJoin($token->getValue(), $token->getExpires()->modify('+1 day'));
    }

    public function testInvalid(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmationToken($token = $this->createToken())
            ->buildJoinByEmail();

        $this->expectExceptionMessage('invalid_confirmation_token');

        $user->confirmJoin('invalid_token', $token->getExpires()->modify('-1 day'));
    }

    public function testAlreadyActive(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmationToken($token = $this->createToken())
            ->active()
            ->buildJoinByEmail();

        $this->expectExceptionMessage('confirmation_not_required');

        $user->confirmJoin($token->getValue(), $token->getExpires()->modify('-1 day'));
    }

    private function createToken(): Token
    {
        return new Token(Uuid::uuid4()->toString(), (new DateTimeImmutable())->modify('+1 day'));
    }
}
