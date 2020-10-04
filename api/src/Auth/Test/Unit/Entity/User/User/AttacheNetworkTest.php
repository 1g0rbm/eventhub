<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\Network;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class AttacheNetworkTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $network = new Network('vk', 'vk-1');
        $user->attachNetwork($network);

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertEquals($network, $networks[0] ?? null);
    }

    public function testAlreadyExist(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $network = new Network('vk', 'vk-1');
        $user->attachNetwork($network);

        $this->expectExceptionMessage('network_attached');

        $user->attachNetwork($network);
    }
}
