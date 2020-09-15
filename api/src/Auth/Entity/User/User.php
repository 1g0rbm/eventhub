<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;

class User
{
    private Id $id;

    private DateTimeImmutable $timestamp;

    private Email $email;

    private string $hash;

    private ?Token $joinConfirmToken;

    public function __construct(
        Id $id,
        DateTimeImmutable $timestamp,
        Email $email,
        string $passwordHash,
        ?Token $joinConfirmToken
    ) {
        $this->id               = $id;
        $this->timestamp        = $timestamp;
        $this->email            = $email;
        $this->hash             = $passwordHash;
        $this->joinConfirmToken = $joinConfirmToken;
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
}
