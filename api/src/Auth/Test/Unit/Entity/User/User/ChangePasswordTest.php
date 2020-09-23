<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Service\PasswordHasher;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $hasher = $this->createHasher(true, $hash = 'hash');

        $user->changePassword(
            'current-password',
            'new-password',
            $hasher
        );

        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testWrongPassword(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $hasher = $this->createHasher(false, $hash = 'hash');

        $this->expectExceptionMessage('incorrect_password');

        $user->changePassword(
            'current-password',
            'new-password',
            $hasher
        );
    }

    public function testNullPassword(): void
    {
        $user = (new UserBuilder())
            ->withNetwork()
            ->build();

        $hasher = $this->createHasher(true, $hash = 'hash');

        $this->expectExceptionMessage('dont_have_password');

        $user->changePassword(
            'current-password',
            'new-password',
            $hasher
        );
    }

    private function createHasher(bool $valid, string $hash): PasswordHasher
    {
        $hasher = $this->createStub(PasswordHasher::class);

        $hasher->method('validate')->willReturn($valid);
        $hasher->method('hash')->willReturn($hash);

        return $hasher;
    }
}
