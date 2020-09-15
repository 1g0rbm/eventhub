<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Email
 */
class EmailTest extends TestCase
{
    public function testSuccess(): void
    {
        $value = 'email@localhost.test';
        $email = new Email('email@localhost.test');

        self::assertEquals($value, $email->getValue());
    }

    public function testCase(): void
    {
        $email = new Email('EMAIL@localhost.test');

        self::assertEquals('email@localhost.test', $email->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email('email');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email('');
    }
}
