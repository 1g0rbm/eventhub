<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Status
{
    public const WAIT = 'wait';
    public const ACTIVE = 'active';

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, [self::WAIT, self::ACTIVE]);

        $this->value = $value;
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public function isWait(): bool
    {
        return $this->value === self::WAIT;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
