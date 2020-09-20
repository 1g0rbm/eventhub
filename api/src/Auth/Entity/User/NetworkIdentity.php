<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

use function mb_strtolower;

class NetworkIdentity
{
    private string $network;
    private string $identity;

    public function __construct(string $network, string $identity)
    {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);

        $this->network  = mb_strtolower($network);
        $this->identity = mb_strtolower($identity);
    }

    public function isEqualTo(self $networkIdentity): bool
    {
        return $this->network === $networkIdentity->getNetwork() && $this->identity === $networkIdentity->getIdentity();
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}
