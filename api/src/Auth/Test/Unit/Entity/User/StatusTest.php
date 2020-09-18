<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $status = new Status(Status::WAIT);

        self::assertEquals(Status::WAIT, $status->getValue());
        self::assertTrue($status->isWait());
        self::assertFalse($status->isActive());
    }

    public function testWaitFactory(): void
    {
        $status = Status::wait();

        self::assertEquals(Status::WAIT, $status->getValue());
        self::assertTrue($status->isWait());
        self::assertFalse($status->isActive());
    }

    public function testActiveFactory(): void
    {
        $status = Status::active();

        self::assertEquals(Status::ACTIVE, $status->getValue());
        self::assertFalse($status->isWait());
        self::assertTrue($status->isActive());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Status('wrong');
    }
}
