<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Auth\Service\PasswordHasher;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table("auth_users")
 */
class User
{
    /**
     * @ORM\Column(type="auth_user_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $timestamp;

    /**
     * @ORM\Column(type="auth_user_email", unique=true)
     */
    private Email $email;

    /**
     * @ORM\Column(type="auth_user_email", nullable=true)
     */
    private ?Email $newEmail = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $hash = null;

    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $joinConfirmToken = null;

    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $passwordResetToken = null;

    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $newEmailToken = null;

    /**
     * @ORM\Column(type="auth_user_status", length=16)
     */
    private Status $status;

    /**
     * @ORM\Column(type="auth_user_role", length=16)
     */
    private Role $role;

    /**
     * @ORM\OneToMany(targetEntity="UserNetwork", mappedBy="user", cascade={all}, orphanRemoval=true)
     */
    private Collection $networks;

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
        $this->networks  = new ArrayCollection();
        $this->role      = Role::user();
    }

    public static function requestJoinByNetwork(
        Id $id,
        DateTimeImmutable $timestamp,
        Email $email,
        Network $network
    ): self {
        $user = new User($id, $timestamp, $email, Status::active());
        $user->networks->add(new UserNetwork($user, $network));

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

    public function remove(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('unable_to_remove_active_user');
        }
    }

    public function confirmEmailChanging(Token $token, DateTimeImmutable $date): void
    {
        $email = $this->newEmail;
        if ($email === null || $this->newEmailToken === null) {
            throw new DomainException('changing_not_requested');
        }

        $this->newEmailToken->validate($token->getValue(), $date);

        $this->email         = $email;
        $this->newEmail      = null;
        $this->newEmailToken = null;
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

    public function changeRole(Role $role): void
    {
        $this->role = $role;
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

    public function attachNetwork(Network $network): void
    {
        $duplicates = array_filter(
            $this->networks->toArray(),
            static fn(UserNetwork $existedNetwork) => $existedNetwork->getNetwork()->isEqualTo($network)
        );

        if (count($duplicates) > 0) {
            throw new DomainException('network_attached');
        }

        $this->networks->add(new UserNetwork($this, $network));
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        /** @var UserNetwork[] $userNetworks */
        $userNetworks = $this->networks->toArray();

        return array_map(
            static fn(UserNetwork $userNetwork): Network => $userNetwork->getNetwork(),
            $userNetworks
        );
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * @ORM\PostLoad
     */
    public function checkEmbeds(): void
    {
        if ($this->joinConfirmToken && $this->joinConfirmToken->isEmpty()) {
            $this->joinConfirmToken = null;
        }

        if ($this->passwordResetToken && $this->passwordResetToken->isEmpty()) {
            $this->passwordResetToken = null;
        }

        if ($this->newEmailToken && $this->newEmailToken->isEmpty()) {
            $this->newEmailToken = null;
        }
    }
}
