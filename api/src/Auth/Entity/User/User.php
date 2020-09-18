<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use DomainException;

class User
{
    private Id $id;

    private DateTimeImmutable $timestamp;

    private Email $email;

    private string $hash;

    private ?Token $joinConfirmToken;

    private Status $status;

    public function __construct(
        Id $id,
        DateTimeImmutable $timestamp,
        Email $email,
        string $passwordHash,
        ?Token $joinConfirmToken = null
    ) {
        $this->id               = $id;
        $this->timestamp        = $timestamp;
        $this->email            = $email;
        $this->hash             = $passwordHash;
        $this->joinConfirmToken = $joinConfirmToken;
        $this->status           = Status::wait();
    }

    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new DomainException('confirmation_not_required');
        }

        $this->joinConfirmToken->validate($token, $date);

        $this->status           = Status::active();
        $this->joinConfirmToken = null;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->hash;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }
}
