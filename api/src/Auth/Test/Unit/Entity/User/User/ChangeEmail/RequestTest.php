<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\ChangeEmail;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->withEmail($oldEmail = new Email('old-email@localhost.test'))
            ->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging(
            $token,
            $now,
            $newEmail = new Email('new-email@localhost.test')
        );

        self::assertNotNull($user->getNewEmailToken());
        self::assertEquals($oldEmail, $user->getEmail());
        self::assertEquals($newEmail, $user->getNewEmail());
    }

    public function testSameEmail(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->withEmail($email = new Email('email@localhost.test'))
            ->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('email_the_same');

        $user->requestEmailChanging($token, $now, $email);
    }

    public function testUserNotActive(): void
    {
        $user = (new UserBuilder())
            ->withEmail($email = new Email('email@localhost.test'))
            ->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('user_not_active');

        $user->requestEmailChanging($token, $now, $email);
    }

    public function testChangeRequested(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->withEmail($oldEmail = new Email('old-email@localhost.test'))
            ->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging(
            $token,
            $now,
            $newEmail = new Email('new-email@localhost.test')
        );

        $this->expectExceptionMessage('changing_already_requested');

        $user->requestEmailChanging($token, $now, $newEmail);
    }

    public function testTokenExpired(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->withEmail($oldEmail = new Email('old-email@localhost.test'))
            ->build();

        $now   = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestEmailChanging(
            $token,
            $now,
            $newEmail = new Email('new-email@localhost.test')
        );

        $newNow   = $now->modify('+2 hours');
        $newToken = $this->createToken($newNow->modify('+1 hour'));

        $user->requestEmailChanging($newToken, $newNow, $newEmail);

        self::assertEquals($newToken, $user->getNewEmailToken());
        self::assertEquals($oldEmail, $user->getEmail());
        self::assertEquals($newEmail, $user->getNewEmail());
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(Uuid::uuid4()->toString(), $date);
    }
}
