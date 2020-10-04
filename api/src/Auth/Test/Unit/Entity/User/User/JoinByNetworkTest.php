<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class JoinByNetworkTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::requestJoinByNetwork(
            $id = Id::generate(),
            $dateTime = new DateTimeImmutable(),
            $email = new Email('email@localhost.test'),
            $network = new Network('vk', 'vk-1')
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($dateTime, $user->getTimestamp());

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertCount(1, $networks = $user->getNetworks());
    }
}
