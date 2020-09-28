<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Auth\Service\PasswordHasher;
use ArrayObject;
use DateTimeImmutable;
use DomainException;

class User
{
    private Id $id;

    private DateTimeImmutable $timestamp;

    private Email $email;

    private ?Email $newEmail = null;

    private ?string $hash = null;

    private ?Token $joinConfirmToken = null;

    private ?Token $passwordResetToken = null;

    private ?Token $newEmailToken = null;

    private Status $status;

    /**
     * @var ArrayObject
     */
    private ArrayObject $networks;

    private function __construct(
        Id $id,
        DateTimeImmutable $timestamp,
        Email $email,
        Status $status
    ) {
        $this->id        = $id;
        $this->timestamp = $timestamp;
        $this->email     = $email;
        $this->status    = $status;
        $this->networks  = new ArrayObject();
    }

    public static function requestJoinByNetwork(
        Id $id,
        DateTimeImmutable $timestamp,
        Email $email,
        NetworkIdentity $networkIdentity
    ): self {
        $user = new User($id, $timestamp, $email, Status::active());
        $user->networks->append($networkIdentity);

        return $user;
    }

    public static function requestJoinByEmail(
        Id $id,
        DateTimeImmutable $timestamp,
        Email $email,
        string $passwordHash,
        Token $joinConfirmToken
    ): self {
        $user                   = new User($id, $timestamp, $email, Status::wait());
        $user->joinConfirmToken = $joinConfirmToken;
        $user->hash             = $passwordHash;

        return $user;
    }

    public function requestEmailChanging(Token $token, DateTimeImmutable $date, Email $email): void
    {
        if (!$this->isActive()) {
            throw new DomainException('user_not_active');
        }

        if ($this->email->isEqualsTo($email)) {
            throw new DomainException('email_the_same');
        }

        if ($this->newEmailToken !== null && !$this->newEmailToken->isExpiresTo($date)) {
            throw new DomainException('changing_already_requested');
        }

        $this->newEmailToken = $token;
        $this->newEmail      = $email;
    }

    public function changePassword(string $current, string $new, PasswordHasher $passwordHasher): void
    {
        if ($this->hash === null) {
            throw new DomainException('dont_have_password');
        }

        if (!$passwordHasher->validate($current, $this->hash)) {
            throw new DomainException('incorrect_password');
        }

        $this->hash = $passwordHasher->hash($new);
    }

    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('resetting_not_requested');
        }

        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->hash               = $hash;
    }

    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('user_not_active');
        }

        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiresTo($date)) {
            throw new DomainException('reset_already_requested');
        }

        $this->passwordResetToken = $token;
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

    public function getPasswordHash(): ?string
    {
        return $this->hash;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    public function attachNetwork(NetworkIdentity $networkIdentity): void
    {
        $duplicates = array_filter(
            $this->networks->getArrayCopy(),
            static fn(NetworkIdentity $existedNetwork) => $existedNetwork->isEqualTo($networkIdentity)
        );

        if (count($duplicates) > 0) {
            throw new DomainException('network_attached');
        }

        $this->networks->append($networkIdentity);
    }

    /**
     * @return NetworkIdentity[]
     */
    public function getNetworks(): array
    {
        /** @var NetworkIdentity[] $networks */
        $networks = $this->networks->getArrayCopy();

        return $networks;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
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
