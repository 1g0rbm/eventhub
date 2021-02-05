<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Auth\Entity\User\Id
 */
class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $value = Uuid::uuid4()->toString();
        $id    = new Id($value);

        self::assertEquals($value, $id->getValue());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();
        $id    = new Id(mb_strtoupper($value));

        self::assertEquals($value, $id->getValue());
    }

    public function testGenerate(): void
    {
        $id = Id::generate();

        self::assertNotEmpty($id->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Id('123456789');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Id('');
    }
}
