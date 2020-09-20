<?php

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\NetworkIdentity;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NetworkIdentityTest extends TestCase
{
    public function testSuccess(): void
    {
        $networkIdentity = new NetworkIdentity($network = 'vk', $identity = 'vk-1');

        self::assertEquals($network, $networkIdentity->getNetwork());
        self::assertEquals($identity, $networkIdentity->getIdentity());
    }

    public function testEmptyNetwork(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new NetworkIdentity($network = '', $identity = 'vk-1');
    }

    public function testEmptyIdentity(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new NetworkIdentity($network = 'vk', $identity = '');
    }

    public function testIsEqualsTo(): void
    {
        $networkIdentity = new NetworkIdentity($network = 'vk', $identity = 'vk-1');

        self::assertTrue($networkIdentity->isEqualTo(new NetworkIdentity('vk', 'vk-1')));
        self::assertFalse($networkIdentity->isEqualTo(new NetworkIdentity('google', 'google-1')));
        self::assertFalse($networkIdentity->isEqualTo(new NetworkIdentity('vk', 'vk-3')));
    }
}
