<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Webmozart\Assert\Assert;

use function mb_strtolower;

/**
 * @ORM\Embeddable
 */
class Token
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $value;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);

        $this->value   = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function validate(string $token, DateTimeImmutable $expires): void
    {
        if ($this->value !== $token) {
            throw new DomainException('invalid_confirmation_token');
        }

        if ($this->expires < $expires) {
            throw new DomainException('expired_confirmation_token');
        }
    }

    public function isExpiresTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}
