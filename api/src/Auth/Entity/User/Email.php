<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

use function mb_strtolower;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);

        $this->value = mb_strtolower($value);
    }

    public function isEqualsTo(self $email): bool
    {
        return $this->value === $email->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
