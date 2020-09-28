<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\ChangeEmail;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $email = new Email('new-email@localhost.test'));

        self::assertNotNull($user->getNewEmail());

        $user->confirmEmailChanging($token, $now);

        self::assertNull($user->getNewEmail());
        self::assertNull($user->getNewEmailToken());
        self::assertEquals($email, $user->getEmail());
    }

    public function testTokenExpired(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now);

        $user->requestEmailChanging($token, $now, $email = new Email('new-email@localhost.test'));

        $this->expectExceptionMessage('expired_confirmation_token');

        $user->confirmEmailChanging($token, $now->modify('+1 day'));
    }

    public function testTokenInvalid(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $email = new Email('new-email@localhost.test'));

        $this->expectExceptionMessage('invalid_confirmation_token');

        $user->confirmEmailChanging(
            new Token(Uuid::uuid4()->toString(), $now->modify('+1 day')),
            $now->modify('+1 hour')
        );
    }

    public function testNotExpired(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now   = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $this->expectExceptionMessage('changing_not_requested');

        $user->confirmEmailChanging($token, $now);
    }
}
