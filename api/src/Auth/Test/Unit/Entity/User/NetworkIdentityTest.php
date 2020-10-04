<?php

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Network;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NetworkIdentityTest extends TestCase
{
    public function testSuccess(): void
    {
        $networkIdentity = new Network($network = 'vk', $identity = 'vk-1');

        self::assertEquals($network, $networkIdentity->getName());
        self::assertEquals($identity, $networkIdentity->getIdentity());
    }

    public function testEmptyNetwork(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Network($network = '', $identity = 'vk-1');
    }

    public function testEmptyIdentity(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Network($network = 'vk', $identity = '');
    }

    public function testIsEqualsTo(): void
    {
        $networkIdentity = new Network($network = 'vk', $identity = 'vk-1');

        self::assertTrue($networkIdentity->isEqualTo(new Network('vk', 'vk-1')));
        self::assertFalse($networkIdentity->isEqualTo(new Network('google', 'google-1')));
        self::assertFalse($networkIdentity->isEqualTo(new Network('vk', 'vk-3')));
    }
}
